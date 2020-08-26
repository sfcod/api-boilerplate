<?php

namespace App\EventListener;

use ApiPlatform\Core\DataProvider\PaginatorInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * Class HeaderPaginationListener
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\EventSubscriber
 */
final class HeaderPaginationListener
{
    /**
     * Add pagination info to headers
     */
    public function __invoke(ResponseEvent $event)
    {
        $request = $event->getRequest();
        $data = $request->attributes->get('data');

        if ($data && $data instanceof PaginatorInterface) {
            $response = $event->getResponse();
            $response->headers->add([
                'X-Total-Count' => $data->getTotalItems(),
                'X-Page-Count' => ceil($data->getTotalItems() / $data->getItemsPerPage()),
                'X-Current-Page' => $data->getCurrentPage(),
                'X-Per-Page' => $data->getItemsPerPage(),
            ]);
        }
    }
}
