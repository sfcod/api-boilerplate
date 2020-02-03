<?php

namespace App\Controller\Actions;

use App\DTO\Band;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BandAction
 *
 * @package App\Controller\Actions
 */
class BandAction
{
    /**
     * Invoke action
     *
     * @Route(
     *     path="/api/band",
     *     name="api_get_band",
     *     methods={"GET"},
     *     defaults={
     *          "_api_respond"=false,
     *          "_api_collection_operation_name"="api_get_band",
     *          "_api_swagger_context"={
     *              "tags"={"Band"},
     *              "parameters"={},
     *          },
     *     }
     * )
     */
    public function __invoke()
    {
        $bands = [];

        return new JsonResponse($bands, 200);
    }
}
