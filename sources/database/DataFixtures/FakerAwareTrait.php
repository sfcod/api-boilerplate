<?php

namespace Database\DataFixtures;

/**
 * Trait FakerAwareTrait
 *
 * @package Portal\DataFixtures
 * @property \Faker\Generator $faker
 */
trait FakerAwareTrait
{
    private $fakerObject;

    /**
     * Magic call for faker
     *
     * @param $name
     * @param $args
     *
     * @return \Faker\Generator
     */
    public function __call($name, $args)
    {
        if ('faker' === $name) {
            return $this->getFaker();
        }

        return parent::__call($name, $args);
    }

    /**
     * Get faker instance
     *
     * @return \Faker\Generator
     */
    protected function getFaker()
    {
        if (!$this->fakerObject) {
            $this->fakerObject = \Faker\Factory::create();
        }

        return $this->fakerObject;
    }

    public function __get($name)
    {
        if ('faker' === $name) {
            return $this->getFaker();
        }

        return parent::__get($name);
    }
}
