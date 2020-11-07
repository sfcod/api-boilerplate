<?php

namespace App\Serializer;

use App\Annotation\AgrNormalizer;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ItemNormalizer
 *
 * @package App\Serializer
 *
 * @author Dmitry Turchanin
 */
final class ItemNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    /**
     * @var DenormalizerInterface|NormalizerInterface
     */
    private $decorated;

    /**
     * @var iterable
     */
    private $normalizers;

    /**
     * ItemNormalizer constructor.
     */
    public function __construct(NormalizerInterface $decorated, iterable $normalizers)
    {
        if (!$decorated instanceof DenormalizerInterface) {
            throw new \InvalidArgumentException(sprintf('The decorated normalizer must implement the %s.', DenormalizerInterface::class));
        }

        $this->normalizers = $normalizers;
        $this->decorated = $decorated;
    }

    /**
     * @param mixed $data
     * @param null $format
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    /**
     * @param mixed $object
     * @param null $format
     *
     * @return array|bool|float|int|string
     *
     * @throws ReflectionException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Exception
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->decorated->normalize($object, $format, $context);

        $annotationReader = new AnnotationReader();
        $reflectionClass = new ReflectionClass(get_class($object));

        // Some SF bug, if move this into __construct then nested loop
        if (false === is_array($this->normalizers)) {
            $normalizers = [];
            foreach ($this->normalizers as $normalizer) {
                $normalizers[trim(get_class($normalizer))] = $normalizer;
            }
            $this->normalizers = $normalizers;
        }

        $classAnnotations = $annotationReader->getClassAnnotations($reflectionClass);
        foreach ($classAnnotations as $classAnnotation) {
            if ($classAnnotation instanceof AgrNormalizer) {
                if (false === empty(array_intersect($classAnnotation->groups, (array)$context['groups']))) {
                    $normalizerClass = trim($classAnnotation->normalizer, '/');
                    if (false === isset($this->normalizers[$normalizerClass])) {
                        throw new \Exception(sprintf('Can not find normalizer %s', $normalizerClass));
                    }

                    $this->normalizers[$normalizerClass]->normalize($object, $data, $context);
                }
            }
        }

        return $data;
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param null $format
     *
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $this->decorated->supportsDenormalization($data, $type, $format);
    }

    /**
     * @param mixed $data
     * @param string $class
     * @param null $format
     *
     * @return object
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return $this->decorated->denormalize($data, $class, $format, $context);
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        if ($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }
}
