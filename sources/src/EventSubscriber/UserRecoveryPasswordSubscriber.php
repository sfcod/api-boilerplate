<?php

namespace App\EventSubscriber;

use App\Event\UserRecoveryPasswordEvent;
use App\Service\Env;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

/**
 * Class RecoveryPasswordListener
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\EventListener
 */
final class UserRecoveryPasswordSubscriber implements EventSubscriberInterface
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
     */
    public function __construct(MailerInterface $mailer, Env $env)
    {
        $this->mailer = $mailer;
        $this->env = $env;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            UserRecoveryPasswordEvent::class => ['onUserRecoveryPassword'],
        ];
    }

    /**
     * Send email to user with recovery token
     *
     * @throws TransportExceptionInterface
     */
    public function onUserRecoveryPassword(UserRecoveryPasswordEvent $event)
    {
        $email = (new TemplatedEmail())
            ->from($this->env->get('MAILER_FROM'))
            ->to($event->getUser()->getEmail())
            ->subject('Password recovery')
            ->htmlTemplate('emails/recovery_password.html.twig')
            ->context([
                'token' => $event->getToken(),
                'fullName' => $event->getUser()->getFullName(),
            ]);

        $this->mailer->send($email);
    }
}
