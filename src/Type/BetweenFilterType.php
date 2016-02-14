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
        $lowerBound = $this->field . '_since';
        $upperBound = $this->field . '_until';

        $filters->set($lowerBound, $this->getGreaterThanFilter());
        $filters->set($upperBound, $this->getLessThanFilter());
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'field' => null,
            'include_upper_bound' => true,
            'include_lower_bound' => true,
        ]);
    }

    /**
     * @return AbstractFilterType
     */
    protected function getGreaterThanFilter()
    {
        $filterClass = $this->options['include_lower_bound']
            ? GreaterThanEqualFilterType::class
            : GreaterThanFilterType::class;

        return new $filterClass($this->field, $this->getCleanOptions());
    }

    /**
     * @return AbstractFilterType
     */
    protected function getLessThanFilter()
    {
        $filterClass = $this->options['include_upper_bound']
            ? LessThanEqualFilterType::class
            : LessThanFilterType::class;

        return new $filterClass($this->field, $this->getCleanOptions());
    }

    protected function getCleanOptions()
    {
        $options = $this->options;
        unset($options['include_lower_bound']);
        unset($options['include_upper_bound']);

        return $options;
    }
}
