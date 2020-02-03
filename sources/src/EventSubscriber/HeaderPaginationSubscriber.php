<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\DataProvider\PaginatorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class HeaderPaginationSubscriber
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\EventSubscriber
 */
class HeaderPaginationSubscriber implements EventSubscriberInterface
{
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
            KernelEvents::RESPONSE => ['addPaginationHeaders'],
        ];
    }

    /**
     * Add pagination info to headers
     */
    public function addPaginationHeaders(ResponseEvent $event)
    {
        $request = $event->getRequest();
        $data = $request->attributes->get('data');

        if ($data && $data instanceof PaginatorInterface) {
            $response = $event->getResponse();
            $response->headers->add([
                'X-Pagination-Total-Count' => $data->getTotalItems(),
                'X-Pagination-Page-Count' => ceil($data->getTotalItems() / $data->getItemsPerPage()),
                'X-Pagination-Current-Page' => $data->getCurrentPage(),
                'X-Pagination-Per-Page' => $data->getItemsPerPage(),
            ]);
        }
    }
}
