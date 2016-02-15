<?php

namespace Fludio\DoctrineFilter\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Fludio\DoctrineFilter\FilterBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BetweenFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table, $field)
    {
    }

    public function addToFilters(ArrayCollection $filters)
    {
        $upperBound = $this->fields . '_' . $this->options['upper_bound_suffix'];
        $lowerBound = $this->fields . '_' . $this->options['lower_bound_suffix'];

        $filters->set($lowerBound, $this->getGreaterThanFilter());
        $filters->set($upperBound, $this->getLessThanFilter());
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('upper_bound_suffix', 'until');
        $resolver->setDefault('lower_bound_suffix', 'since');
        $resolver->setDefault('include_upper_bound', true);
        $resolver->setDefault('include_lower_bound', true);
    }

    /**
     * @return AbstractFilterType
     */
    protected function getGreaterThanFilter()
    {
        $filterClass = $this->options['include_lower_bound']
            ? GreaterThanEqualFilterType::class
            : GreaterThanFilterType::class;

        return new $filterClass($this->fields, $this->getCleanOptions());
    }

    /**
     * @return AbstractFilterType
     */
    protected function getLessThanFilter()
    {
        $filterClass = $this->options['include_upper_bound']
            ? LessThanEqualFilterType::class
            : LessThanFilterType::class;

        return new $filterClass($this->fields, $this->getCleanOptions());
    }

    protected function getCleanOptions()
    {
        $options = $this->options;
        unset($options['include_lower_bound']);
        unset($options['include_upper_bound']);
        unset($options['upper_bound_suffix']);
        unset($options['lower_bound_suffix']);

        return $options;
    }
}
