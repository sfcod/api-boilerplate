<?php

namespace App\Controller\Actions\User;

use App\Controller\Common\TransformJsonBodyTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserItem extends AbstractController
{
    use TransformJsonBodyTrait;

    public function __invoke()
    {
        return $this->getUser();
    }
}
