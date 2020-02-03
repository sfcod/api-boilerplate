<?php

namespace App\Controller;

use App\Controller\Common\TransformJsonBody;
use App\Entity\User;
use App\FormType\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\Controller
 */
class UserController extends AbstractController
{
    use TransformJsonBody;

    /**
     * @Route(
     *     name="get_profile",
     *     path="/api/profile",
     *     methods={"GET"},
     *     defaults={
     *         "_api_resource_class"=User::class,
     *         "_api_item_operation_name"="get_profile",
     *         "_api_receive"=false,
     *     }
     * )
     *
     * @return object|User|null
     */
    public function getProfileAction()
    {
        return $this->getUser();
    }

    /**
     * @Route(
     *     name="update_profile",
     *     path="/api/profile",
     *     methods={"PUT"},
     *     defaults={
     *         "_api_resource_class"=User::class,
     *         "_api_collection_operation_name"="update_profile",
     *     }
     * )
     *
     * @return object|User|null
     */
    public function updateProfileAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->transformJsonBody($request);

        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user, [
            'method' => 'PUT',
            'allow_extra_fields' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user = $form->getData();

            return $user;
        }

        return $this->json(['message' => 'Bad request', Response::HTTP_BAD_REQUEST]);
    }
}
