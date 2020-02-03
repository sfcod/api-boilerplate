<?php

namespace App\Controller\Common;

use Symfony\Component\HttpFoundation\Request;

/**
 * Trait TransformJsonBody
 *
 * Transmits json from content to request
 *
 * @package App\Controller\Common
 */
trait TransformJsonBody
{
    /**
     * @return bool
     */
    private function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return false;
        }

        if (null === $data) {
            return true;
        }

        $request->request->replace($data);

        return true;
    }
}
