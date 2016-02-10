<?php

namespace Fludio\DoctrineFilter\Tests\Filter\Type;

use Fludio\DoctrineFilter\Type\AbstractFilterType;

class AbstractFilterTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_sets_the_correct_values()
    {
        $name = 'height';
        $options = [
            'some' => 'options',
            'field' => 'person.height'
        ];
        /** @var AbstractFilterType $filterType */
        $filterType = $this->getMockForAbstractClass(AbstractFilterType::class, [$name, $options]);

        $this->assertEquals($name, $filterType->getName());
        $this->assertEquals('person.height', $filterType->getField());
        $this->assertEquals($options, $filterType->getOptions());
    }

}
