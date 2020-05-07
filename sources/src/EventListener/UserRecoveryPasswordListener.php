<?php

namespace App\EventListener;

use App\Event\UserRecoveryPasswordEvent;
use App\Service\Env;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
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
     * @var Env
     */
    private $env;

    /**
     * RecoveryPasswordListener constructor.
     * @param MailerInterface $mailer
     * @param Env $env
     */
    public function __construct(MailerInterface $mailer, Env $env)
    {
        $this->mailer = $mailer;
        $this->env = $env;
    }

    /**
     * Send email to user with recovery token
     *
     * @param UserRecoveryPasswordEvent $event
     * @throws TransportExceptionInterface
     */
    public function __invoke(UserRecoveryPasswordEvent $event)
    {
        $email = (new TemplatedEmail())
            ->from($this->env->get('MAILER_FROM'))
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
