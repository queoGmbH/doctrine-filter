<?php

namespace BiteCodes\DoctrineFilter\Tests\Dummy;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Version;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestDb
     */
    protected $testDb;

    /**
     * @var EntityManager
     */
    protected $em;

    public function setUp()
    {
        parent::setUp();

        $here = dirname(__FILE__);

        $paths = [$here . '/Entity'];

        if ($this->isDoctrineVersion('2.5')) {
            $paths[] = $here . '/Entity25';
        }

        $this->testDb = new TestDb(
            $paths,
            $here . '/TestProxy',
            'BiteCodes\DoctrineFilter\Tests\Dummy\Entity'
        );

        $this->em = $this->testDb->createEntityManager();
    }

    /**
     * @param $version
     * @return bool
     */
    public function isDoctrineVersion($version)
    {
        return Version::compare($version) <= 0;
    }


    protected function seeInDatabase($entity, $criteria)
    {
        $count = $this->getDatabaseCount($entity, $criteria);

        $this->assertGreaterThan(0, $count, sprintf(
            'Unable to find row in database table [%s] that matched attributes [%s].', $entity, json_encode($criteria)
        ));

        return $this;
    }

    protected function seeNotInDatabase($entity, $criteria)
    {
        $count = $this->getDatabaseCount($entity, $criteria);

        $this->assertEquals(0, $count, sprintf(
            'Found row in database table [%s] that matched attributes [%s].', $entity, json_encode($criteria)
        ));

        return $this;
    }

    protected function getDatabaseCount($entity, $criteria)
    {
        $qb = $this->em
            ->createQueryBuilder()
            ->select('COUNT(e)')
            ->from($entity, 'e');

        foreach ($criteria as $field => $value) {
            $qb->andWhere("e.{$field} = :{$field}")->setParameter($field, $value);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
}