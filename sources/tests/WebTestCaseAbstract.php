<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class WebTestCaseAbstract
 *
 * @package App\Tests
 */
abstract class WebTestCaseAbstract extends WebTestCase
{
    /**
     * @var string
     */
    protected static $class = \App\Kernel::class;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var \Faker\Generator
     */
    protected $faker;

//    /**
//     * Creates a KernelBrowser.
//     *
//     * @param array $options An array of options to pass to the createKernel method
//     * @param array $server An array of server parameters
//     *
//     * @return KernelBrowser A KernelBrowser instance
//     */
//    protected static function createClient(array $options = [], array $server = [])
//    {
//        try {
//            /** @var KernelBrowser $client */
//            $client = static::$container->get('test.client');
//        } catch (ServiceNotFoundException $e) {
//            if (class_exists(KernelBrowser::class)) {
//                throw new \LogicException('You cannot create the client used in functional tests if the "framework.test" config is not set to true.');
//            }
//            throw new \LogicException('You cannot create the client used in functional tests if the BrowserKit component is not available. Try running "composer require symfony/browser-kit"');
//        }
//
//        $client->setServerParameters($server);
//
//        return $client;
//    }

    /**
     * Set up kernel test cases
     */
    public function setUp()
    {
        parent::setUp();

        self::bootKernel();

        $this->faker = \Faker\Factory::create();

        $this->em = static::$container
            ->get('doctrine')
            ->getManager();

        $this->em->beginTransaction();
    }

    /**
     * After each test, a rollback reset the state of
     * the database
     */
    protected function tearDown()
    {
        parent::tearDown();

        try {
            $this->em->rollback();
            $this->em->close();
        } catch (Exception $e) {
            // Nothing
        }
    }
}
