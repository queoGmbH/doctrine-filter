<?php

namespace Fludio\DoctrineFilter\Tests\Dummy\Traits;

use Fludio\DoctrineFilter\Tests\Dummy\Filter\TestFilter;

trait TestFilterTrait
{
    protected $filter;

    /**
     * @before
     */
    public function setUpTestFilter()
    {
        $this->filter = new TestFilter();
        $this->filter->defineFilter($this->getFilterDefinition());
    }

    abstract public function getFilterDefinition();
}