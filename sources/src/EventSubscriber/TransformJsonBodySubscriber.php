<?php

namespace App\EventSubscriber;

use App\Controller\Common\TransformJsonBodyTrait;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Transforms the body of a json request to POST/PATCH parameters.
 */
class TransformJsonBodySubscriber implements EventSubscriberInterface
{
    use TransformJsonBodyTrait;

    /** @var Reader */
    private $reader;

    /**
     * @var bool
     */
    private $isGlobal;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * List subscribed events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * @throws ReflectionException
     */
    public function onKernelController(ControllerEvent $event)
    {
        if (!is_array($controllers = $event->getController())) {
            return;
        }

        $request = $event->getRequest();
        if (!$this->isJsonRequest($request)) {
            return;
        }

        $content = $request->getContent();
        if (empty($content)) {
            return;
        }

        $isGlobal = $this->isGlobal;
        list($controller, $methodName) = $controllers;

        $reflectionClass = new ReflectionClass($controller);
        $classAnnotation = $this->reader
            ->getClassAnnotation($reflectionClass, TransformJsonBody::class);

        $reflectionObject = new ReflectionObject($controller);
        $reflectionMethod = $reflectionObject->getMethod($methodName);
        $methodAnnotation = $this->reader
            ->getMethodAnnotation($reflectionMethod, TransformJsonBody::class);

        if (!($classAnnotation || $methodAnnotation) && !$isGlobal) {
            return;
        }

        if (!$this->transformJsonBody($request)) {
            $response = Response::create('Unable to parse request.', 400);
            $controller->setResponse($response);
        }
    }

    /**
     * @return bool
     */
    private function isJsonRequest(Request $request)
    {
        return 'json' === $request->getContentType();
    }

    public function setIsGlobal(bool $value)
    {
        $this->isGlobal = $value;
    }
}
