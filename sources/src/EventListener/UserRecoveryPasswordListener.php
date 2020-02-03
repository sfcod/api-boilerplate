<?php

namespace App\EventListener;

use App\Event\UserRecoveryPasswordEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

/**
 * Class RecoveryPasswordListener
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\EventListener
 */
final class UserRecoveryPasswordListener
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * RecoveryPasswordListener constructor.
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send email to user with recovery token
     *
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function __invoke(UserRecoveryPasswordEvent $event)
    {
        $email = (new TemplatedEmail())
            ->from(getenv('MAILER_FROM'))
            ->to($event->getUser()->getEmail())
            ->subject('Password recovery')
            ->htmlTemplate('email/recovery_password.html.twig')
            ->context([
                'token' => $event->getToken(),
                'fullName' => $event->getUser()->getFullName(),
            ]);

        $this->mailer->send($email);
    }
}
