<?php

namespace Queo\DoctrineFilter\Tests\Dummy\Traits;

use Queo\DoctrineFilter\Tests\Dummy\Filter\TestFilter;

trait TestFilterTrait
{
    /**
     * @var TestFilter
     */
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