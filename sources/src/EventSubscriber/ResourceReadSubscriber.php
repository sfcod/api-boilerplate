<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Annotation\ApiRequiredFilters;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ResourceReadSubscriber
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\EventSubscriber
 */
class ResourceReadSubscriber implements EventSubscriberInterface
{
    private AnnotationReader $annotationReader;

    private TranslatorInterface $translator;

    /**
     * ResourceReadSubscriber constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->annotationReader = new AnnotationReader();
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => [
                ['hasFilter', EventPriorities::PRE_READ],
            ],
        ];
    }

    /**
     * Check request to have all the filters needed by using the custom annotation.
     * Works only for api operations.
     *
     * @throws \ReflectionException
     */
    public function hasFilter(ControllerEvent $event)
    {
        $attributes = $event->getRequest()->attributes;

        $action = $attributes->get('_api_collection_operation_name');
        $resource = $attributes->get('_api_resource_class');

        if (!$action || !$resource) {
            return;
        }

        $reflection = new ReflectionClass($resource);
        $classAnnotations = $this->annotationReader->getClassAnnotations($reflection);

        foreach ($classAnnotations as $classAnnotation) {
            if ($classAnnotation instanceof ApiRequiredFilters) {
                if ($action !== $classAnnotation->action) {
                    continue;
                }

                foreach ($classAnnotation->filters as $filter) {
                    if (!$event->getRequest()->query->has($filter)) {
                        throw new BadRequestHttpException($this->translator->trans('Filter \'{filterName}\' is required.', ['{filterName}' => $filter]));
                    }
                }
            }
        }
    }
}
