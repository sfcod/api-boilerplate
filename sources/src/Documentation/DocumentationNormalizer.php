<?php

namespace App\Documentation;

use App\Documentation\Components\SchemaInterface;
use App\Documentation\Components\UpdatableSchemaInterface;
use App\Documentation\Helpers\ArrayHelper;
use App\Documentation\Paths\PathInterface;
use App\Documentation\Paths\ReplacePathInterface;
use App\Documentation\Paths\UnsupportedParamsInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class DocumentationNormalizer
 *
 * @package App\Documentation
 */
final class DocumentationNormalizer implements NormalizerInterface
{
    /**
     * @var NormalizerInterface
     */
    private $decorated;

    /**
     * @var PathInterface[]
     */
    private $paths = [];

    /**
     * @var PathInterface[]
     */
    private $hiddenPaths = [];

    /**
     * @var UpdatableSchemaInterface[]
     */
    private $updatableSchemas = [];

    /**
     * @var SchemaInterface[]
     */
    private $schemas = [];

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * @param PathInterface[] $paths
     */
    public function setPaths($paths)
    {
        $this->paths = $paths;
    }

    /**
     * @param SchemaInterface[] $schemas
     */
    public function setSchemas($schemas)
    {
        $this->schemas = $schemas;
    }

    /**
     * @param UpdatableSchemaInterface[] $updatableSchemas
     */
    public function setUpdatableSchemas($updatableSchemas)
    {
        $this->updatableSchemas = $updatableSchemas;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $documentation = [];
        foreach ($this->paths as $path) {
            if (!isset($documentation['paths'][$path->getPath()])) {
                $documentation['paths'][$path->getPath()] = [];
            }

            $documentation['paths'][$path->getPath()][$path->getMethod()] = $path->getParams();
        }

        foreach ($this->schemas as $schema) {
            $documentation['components']['schemas'][$schema->getName()] = $schema->getSchema();
        }

        $original = $this->decorated->normalize($object, $format, $context);

        $data = ArrayHelper::merge($original, $documentation);

        foreach ($this->hiddenPaths as $path) {
            if (isset($data['paths'][$path])) {
                unset($data['paths'][$path]);
            }
        }
        foreach ($this->updatableSchemas as $schema) {
            foreach ($schema->getSchemas() as $schemaName) {
                if (isset($data['components']['schemas'][$schemaName])) {
                    foreach ($schema->getProperties() as $i => $property) {
                        $data['components']['schemas'][$schemaName]['properties'][$i] = $property;
                    }
                }
            }
        }

        foreach ($this->paths as $path) {
            if (is_a($path, UnsupportedParamsInterface::class)) {
                foreach ($data['paths'][$path->getPath()][$path->getMethod()]['parameters'] as $i => $parameter) {
                    if (in_array($parameter['name'], $path->getUnusedParams())) {
                        unset($data['paths'][$path->getPath()][$path->getMethod()]['parameters'][$i]);
                    }
                }
            }
            if (is_a($path, ReplacePathInterface::class)) {
                foreach ($data['paths'][$path->getPath()] as $i => $parameter) {
                    // @phpstan-ignore-next-line
                    $data['paths'][$path->getPath()] = $documentation['paths'][$path->getPath()];
                }
            }
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    public function setHiddenPaths(array $hiddenPaths): void
    {
        $this->hiddenPaths = $hiddenPaths;
    }
}
