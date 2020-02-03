<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdminUserVoter extends Voter
{
    // these strings are just invented: you can use anything
    const ADMIN_USERS_DELETE = 'ROLE_ADMIN_USERS_DELETE';
    const ADMIN_USERS_CHANGE_ROLE = 'ROLE_ADMIN_USERS_CHANGE_ROLE';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::ADMIN_USERS_DELETE, self::ADMIN_USERS_CHANGE_ROLE])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::ADMIN_USERS_DELETE:
                return $this->canDelete($subject, $user);
            case self::ADMIN_USERS_CHANGE_ROLE:
                return $this->canChangeRole($subject, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canDelete(User $subject, User $user)
    {
        return $subject->getId() !== $user->getId();
    }

    private function canChangeRole(User $subject, User $user)
    {
        return $subject->getId() !== $user->getId();
    }
}
