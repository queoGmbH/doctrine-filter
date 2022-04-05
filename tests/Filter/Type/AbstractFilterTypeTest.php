<?php

namespace BiteCodes\DoctrineFilter\Tests\Filter\Type;

use BiteCodes\DoctrineFilter\Type\AbstractFilterType;
use BiteCodes\DoctrineFilter\Type\BetweenFilterType;
use BiteCodes\DoctrineFilter\Type\ComparableFilterType;
use BiteCodes\DoctrineFilter\Type\EqualFilterType;
use BiteCodes\DoctrineFilter\Type\GreaterThanEqualFilterType;
use BiteCodes\DoctrineFilter\Type\GreaterThanFilterType;
use BiteCodes\DoctrineFilter\Type\InFilterType;
use BiteCodes\DoctrineFilter\Type\InstanceOfFilterType;
use BiteCodes\DoctrineFilter\Type\LessThanEqualFilterType;
use BiteCodes\DoctrineFilter\Type\LessThanFilterType;
use BiteCodes\DoctrineFilter\Type\LikeFilterType;
use BiteCodes\DoctrineFilter\Type\NotEqualFilterType;
use BiteCodes\DoctrineFilter\Type\NotInFilterType;
use BiteCodes\DoctrineFilter\Type\OrderByType;
use PHPUnit\Framework\TestCase;

class AbstractFilterTypeTest extends TestCase
{
    /** @test */
    public function it_sets_the_correct_values()
    {
        $name = 'height';
        $options = [
            'default' => null,
            'default_override' => false,
            'fields' => 'person.height',
            'match_all_fields' => false,
            'partial_match' => false
        ];
        /** @var AbstractFilterType $filterType */
        $filterType = $this->getMockForAbstractClass(AbstractFilterType::class, [$name, $options]);

        $this->assertEquals($name, $filterType->getName());
        $this->assertEquals('person.height', $filterType->getFields());
        $this->assertEquals($options, $filterType->getOptions());
    }

    /**
     * @test
     * @dataProvider filters
     */
    public function all_filters_have_fields_default_and_default_override_option($filterClass)
    {
        $filter = new $filterClass('name', [
            'default' => 'some',
            'default_override' => false,
            'fields' => 'some',
        ]);

        $this->assertInstanceOf(AbstractFilterType::class, $filter);
    }

    /**
     * @return array
     */
    public function filters()
    {
        return [
            [BetweenFilterType::class],
            [ComparableFilterType::class],
            [EqualFilterType::class],
            [GreaterThanEqualFilterType::class],
            [GreaterThanFilterType::class],
            [InFilterType::class],
            [InstanceOfFilterType::class],
            [LessThanEqualFilterType::class],
            [LessThanFilterType::class],
            [LikeFilterType::class],
            [NotEqualFilterType::class],
            [NotInFilterType::class],
            [OrderByType::class],
        ];
    }
}
