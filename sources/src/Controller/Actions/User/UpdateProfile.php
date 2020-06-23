<?php

namespace App\Controller\Actions\User;

use App\Controller\Common\TransformJsonBodyTrait;
use App\FormType\ProfileType;
use App\Service\FormUtility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UpdateProfile extends AbstractController
{
    use TransformJsonBodyTrait;

    public function __invoke(Request $request, UserPasswordEncoderInterface $passwordEncoder, FormUtility $formUtility)
    {
        $this->transformJsonBody($request);

        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user, [
            'method' => 'PUT',
            'allow_extra_fields' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            return $user;
        }

        return new JsonResponse($formUtility->formatErrors($form), 400);
    }
}
