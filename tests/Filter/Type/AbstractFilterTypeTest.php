<?php

namespace Fludio\DoctrineFilter\Tests\Filter\Type;

use Fludio\DoctrineFilter\Type\AbstractFilterType;
use Fludio\DoctrineFilter\Type\BetweenFilterType;
use Fludio\DoctrineFilter\Type\ComparableFilterType;
use Fludio\DoctrineFilter\Type\EqualFilterType;
use Fludio\DoctrineFilter\Type\GreaterThanEqualFilterType;
use Fludio\DoctrineFilter\Type\GreaterThanFilterType;
use Fludio\DoctrineFilter\Type\InFilterType;
use Fludio\DoctrineFilter\Type\InstanceOfFilterType;
use Fludio\DoctrineFilter\Type\LessThanEqualFilterType;
use Fludio\DoctrineFilter\Type\LessThanFilterType;
use Fludio\DoctrineFilter\Type\LikeFilterType;
use Fludio\DoctrineFilter\Type\NotEqualFilterType;
use Fludio\DoctrineFilter\Type\NotInFilterType;
use Fludio\DoctrineFilter\Type\OrderByType;

class AbstractFilterTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_sets_the_correct_values()
    {
        $name = 'height';
        $options = [
            'field' => 'person.height',
            'description' => 'Search for the height of a person'
        ];
        /** @var AbstractFilterType $filterType */
        $filterType = $this->getMockForAbstractClass(AbstractFilterType::class, [$name, $options]);

        $this->assertEquals($name, $filterType->getName());
        $this->assertEquals('person.height', $filterType->getField());
        $this->assertEquals($options, $filterType->getOptions());
    }

    /**
     * @test
     * @dataProvider filters
     */
    public function all_filters_have_a_field_and_description_option($filterClass)
    {
        $filter = new $filterClass('name', [
            'field' => 'some',
            'description' => 'some'
        ]);

        $this->assertInstanceOf(AbstractFilterType::class, $filter);
    }

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
