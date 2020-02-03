<?php

namespace App\Documentation;

use App\Documentation\Definitions\DefinitionObjectInterface;
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
     * @var DefinitionObjectInterface[]
     */
    private $definitions;

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
     * @param DefinitionObjectInterface[] $definitions
     */
    public function setDefinitions($definitions)
    {
        $this->definitions = $definitions;
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
        foreach ($this->definitions as $definition) {
            $documentation['definitions'][$definition->getName()] = $definition->getParams();
        }

        $original = $this->decorated->normalize($object, $format, $context);

        $data = array_merge_recursive($original, $documentation);
        foreach ($this->hiddenPaths as $path) {
            if (isset($data['paths'][$path])) {
                unset($data['paths'][$path]);
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
