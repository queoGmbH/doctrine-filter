<?php

namespace Queo\DoctrineFilter\Tests\Filter\Type;

use Queo\DoctrineFilter\Type\AbstractFilterType;
use Queo\DoctrineFilter\Type\BetweenFilterType;
use Queo\DoctrineFilter\Type\ComparableFilterType;
use Queo\DoctrineFilter\Type\EqualFilterType;
use Queo\DoctrineFilter\Type\GreaterThanEqualFilterType;
use Queo\DoctrineFilter\Type\GreaterThanFilterType;
use Queo\DoctrineFilter\Type\InFilterType;
use Queo\DoctrineFilter\Type\InstanceOfFilterType;
use Queo\DoctrineFilter\Type\LessThanEqualFilterType;
use Queo\DoctrineFilter\Type\LessThanFilterType;
use Queo\DoctrineFilter\Type\LikeFilterType;
use Queo\DoctrineFilter\Type\NotEqualFilterType;
use Queo\DoctrineFilter\Type\NotInFilterType;
use Queo\DoctrineFilter\Type\OrderByType;
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
