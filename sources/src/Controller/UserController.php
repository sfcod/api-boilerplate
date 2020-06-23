<?php

namespace App\Controller;

use App\Controller\Common\TransformJsonBodyTrait;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 *
 * This is example how add action via controller.
 *
 * @deprecated pls use actions implementation
 *
 * @package App\Controller
 */
class UserController extends AbstractController
{
    use TransformJsonBodyTrait;

    /**
     * Entity:
     *      ...
     *         "get_profile"={
     *             "method"="GET",
     *             "route_name"="get_profile",
     *             "normalization_context"={"groups"={"user:profile:read"}},
     *             "openapi_context"={
     *                 "summary"="Retreive the current User resource.",
     *                 "parameters"={},
     *             },
     *         },
     *      ...
     */

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

    /**
     * Here you can put route above.
     *
     * @return object|\Symfony\Component\Security\Core\User\UserInterface|null
     */
    public function getProfileAction()
    {
        return $this->getUser();
    }
}
