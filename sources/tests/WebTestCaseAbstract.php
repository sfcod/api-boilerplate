<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class WebTestCaseAbstract
 *
 * @package App\Tests
 */
abstract class WebTestCaseAbstract extends WebTestCase
{
    use KernelClient;

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

    /**
     * @var bool
     */
    protected $transaction = true;

    /**
     * Set up kernel test cases
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->faker = \Faker\Factory::create();

        $this->em = $this->getKernelClient()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        if ($this->isTransactionOn()) {
            $this->em->beginTransaction();
        }
    }

    protected function isTransactionOn(): bool
    {
        return true;
    }

    /**
     * After each test, a rollback reset the state of
     * the database
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->isTransactionOn()) {
            try {
                $this->em->rollback();
                $this->em->close();
            } catch (Exception $e) {
                // Nothing
            }
        }
    }
}
