<?php

namespace App\Controller;

use App\Controller\Common\TransformJsonBodyTrait;
use App\Entity\User;
use App\Event\UserRecoveryPasswordEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function logout()
    {
    }

    use TransformJsonBodyTrait;

    /**
     * @Route(
     *     name="forgot_password",
     *     path="/api/forgot-password",
     *     methods={"POST"},
     * )
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function forgotPasswordAction(Request $request, EventDispatcherInterface $dispatcher)
    {
        $this->transformJsonBody($request);

        /** @var User $user */
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->loadUserByUsername($request->request->get('username'));

        if (!$user) {
            return $this->json(['message' => 'User was not found.'], 404);
        }

        $token = substr(md5(random_bytes(10)), -6);
        $user->setRecoveryPasswordToken($token);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $dispatcher->dispatch(new UserRecoveryPasswordEvent($user, $token));

        return $this->json([
//            'message' => sprintf('A recovery code was sent to "%s"', $user->getEmail()),
            // Very "secure" change
            'message' => 'A recovery code was sent to your email',
        ]);
    }

    /**
     * @Route(
     *     name="forgot_password_validate_token",
     *     path="/api/forgot-password/validate-token",
     *     methods={"POST"},
     * )
     *
     * @return JsonResponse
     */
    public function validateRecoveryTokenAction(Request $request, JWTTokenManagerInterface $tokenManager)
    {
        $this->transformJsonBody($request);

        /** @var User|null $user */
        $user = $this->getDoctrine()->getRepository(User::class)
            ->findOneBy(['recoveryPasswordToken' => $request->request->get('token')]);

        if (!$user) {
            return $this->json(['message' => 'Invalid code.'], 404);
        }

        return $this->json(['token' => $tokenManager->create($user)], 200);
    }

    /**
     * @Route(
     *     name="reset_password",
     *     path="/api/users/reset-password",
     *     methods={"POST"},
     *     defaults={
     *         "_api_resource_class"=User::class,
     *         "_api_item_operation_name"="reset_password",
     *         "_api_receive"=false,
     *         "_api_respond"=false,
     *     }
     * )
     *
     * @return JsonResponse
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->transformJsonBody($request);

        /** @var User $user */
        $user = $this->getUser();
        $plainPassword = $request->request->get('password');
        $user->setPassword($passwordEncoder->encodePassword($user, $plainPassword));
        $user->setRecoveryPasswordToken(null);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json([], 204);
    }
}
