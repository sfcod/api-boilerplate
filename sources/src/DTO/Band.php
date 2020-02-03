<?php

namespace App\DTO;

use ApiPlatform\Core\Annotation\ApiResource;

/**
 * Class Band
 *
 * @package App\DTO
 *
 * @ApiResource(
 *      attributes={"pagination_enabled"=false},
 *      itemOperations={
 *      },
 *      collectionOperations={
 *          "api_get_band"={
 *              "route_name"="api_get_band",
 *              "method"="GET",
 *              "swagger_context" = {
 *                  "summary" = "Generate band list.",
 *                  "parameters" = {},
 *              }
 *          },
 *     },
 * )
 */
class Band
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;
}
