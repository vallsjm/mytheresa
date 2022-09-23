<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

final class DatabaseContext implements Context
{
    private ObjectManager $entityManager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();

        /** @var EntityManagerInterface $em */
        $em = $doctrine->getManager();

        $schemaTool = new SchemaTool($em);
        $classes = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool->updateSchema($classes);
    }

    /**
     * @BeforeScenario
     */
    public function purgeDatabase(): void
    {
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
        $tables = $this->entityManager->getConnection()->getSchemaManager()->listTableNames();
        $this->entityManager->getConnection()->query('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($tables as $table) {
            $truncateSQL = "TRUNCATE {$table};";
            $this->entityManager->getConnection()->query($truncateSQL);
        }

        $this->entityManager->getConnection()->query('SET FOREIGN_KEY_CHECKS = 1');
        $this->entityManager->clear();
    }
}
