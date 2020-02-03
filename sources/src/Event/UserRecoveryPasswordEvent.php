<?php

namespace App\Event;

use App\Entity\User;

/**
 * Class UserPasswordRecoveryEvent
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\Event
 */
class UserRecoveryPasswordEvent
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $token;

    /**
     * UserPasswordRecoveryEvent constructor.
     */
    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
