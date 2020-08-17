<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserManager
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\Service
 */
class UserManager
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * UserManager constructor.
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
    }

    public function notificationNewUserWasCreated(User $user)
    {
        $message = (new TemplatedEmail())
            ->from(getenv('MAILER_FROM'))
            ->to($user->getEmail())
            ->htmlTemplate('emails/create_user.html.twig')
            ->context([
                'fullName' => $user->getFullName(),
                'email' => $user->getEmail(),
                'plainPassword' => $user->getPlainPassword(),
            ]);

        $this->mailer->send($message);
    }

    /**
     * Update user password hash from plain password
     */
    public function updatePassword(User $user)
    {
        if ($user->getPlainPassword()) {
            $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
        }
    }
}
