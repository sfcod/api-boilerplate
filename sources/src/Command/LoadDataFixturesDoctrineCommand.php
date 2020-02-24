<?php

namespace App\Command;

use Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand as LoadDataFixturesDoctrineCommandAliasBase;
use Doctrine\Bundle\FixturesBundle\Loader\SymfonyFixturesLoader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Sharding\PoolingShardConnection;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LoadDataFixturesDoctrineCommand extends LoadDataFixturesDoctrineCommandAliasBase
{
    /** @var SymfonyFixturesLoader */
    private $fixturesLoader;

    /** @var array */
    private $excluded;

    public function __construct(SymfonyFixturesLoader $fixturesLoader, ?ManagerRegistry $doctrine = null)
    {
        parent::__construct($fixturesLoader, $doctrine);

        $this->fixturesLoader = $fixturesLoader;
    }

    /**
     * @return void
     */
    public function setExcludedTables(array $excluded)
    {
        $this->excluded = $excluded;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ui = new SymfonyStyle($input, $output);

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager($input->getOption('em'));

        /** @var Connection $connection */
        $connection = $em->getConnection();
        if (!$input->getOption('append')) {
            if (!$ui->confirm(sprintf('Careful, database "%s" will be purged. Do you want to continue?', $connection->getDatabase()), !$input->isInteractive())) {
                return 0;
            }
        }

        if ($input->getOption('shard')) {
            if (!$connection instanceof PoolingShardConnection) {
                throw new LogicException(sprintf('Connection of EntityManager "%s" must implement shards configuration.', $input->getOption('em')));
            }

            $connection->connect($input->getOption('shard'));
        }

        $groups = $input->getOption('group');
        $fixtures = $this->fixturesLoader->getFixtures($groups);
        if (!$fixtures) {
            $message = 'Could not find any fixture services to load';

            if (!empty($groups)) {
                $message .= sprintf(' in the groups (%s)', implode(', ', $groups));
            }

            $ui->error($message . '.');

            return 1;
        }

        $purger = new ORMPurger($em, $this->excluded ?? []);
        $purger->setPurgeMode($input->getOption('purge-with-truncate') ? ORMPurger::PURGE_MODE_TRUNCATE : ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($em, $purger);
        $executor->setLogger(static function ($message) use ($ui): void {
            $ui->text(sprintf('  <comment>></comment> <info>%s</info>', $message));
        });
        $executor->execute($fixtures, $input->getOption('append'));

        return 0;
    }
}
