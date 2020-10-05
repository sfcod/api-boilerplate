<?php

namespace App\Serializer;

use App\Serializer\ItemNormalizer\ItemNormalizerInterface;
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
    public function __construct(NormalizerInterface $decorated)
    {
        if (!$decorated instanceof DenormalizerInterface) {
            throw new \InvalidArgumentException(sprintf('The decorated normalizer must implement the %s.', DenormalizerInterface::class));
        }

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
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->decorated->normalize($object, $format, $context);

        /** @var ItemNormalizerInterface $normalizer */
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supports($object)) {
                $normalizer->normalize($object, $data);
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

    /**
     * Sets normalizers
     */
    public function setNormalizers(iterable $normalizers)
    {
        $this->normalizers = $normalizers;
    }
}
