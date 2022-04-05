<?php

namespace Queo\DoctrineFilter\Tests\Dummy;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Version;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TestDb
     */
    protected static $testDb;

    /**
     * @var EntityManager
     */
    protected static $em;


    /** @beforeClass */
    public static function init(): void
    {
        $here = dirname(__FILE__);

        $paths = [$here . '/Entity'];

        if (self::isAtLeastDoctrineVersion('2.5')) {
            $paths[] = $here . '/Entity25';
        }

        self::$testDb = new TestDb(
            $paths,
            $here . '/TestProxy',
            'Queo\DoctrineFilter\Tests\Dummy\Entity'
        );

        self::$em = self::$testDb->createEntityManager();
    }


    /**
     * @param $version
     * @return bool
     */
    public static function isAtLeastDoctrineVersion($version)
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
        $qb = self::$em
            ->createQueryBuilder()
            ->select('COUNT(e)')
            ->from($entity, 'e');

        foreach ($criteria as $field => $value) {
            $qb->andWhere("e.{$field} = :{$field}")->setParameter($field, $value);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
}