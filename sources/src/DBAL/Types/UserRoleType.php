<?php

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * Class UserRoleType
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\DBAL\Types
 */
class UserRoleType extends AbstractEnumType
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_USER = 'ROLE_USER';

    /**
     * @var array
     */
    protected static $choices = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_USER => 'User',
    ];
}
