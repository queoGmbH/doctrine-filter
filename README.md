# Doctrine-Filter

[![Coverage Status](https://coveralls.io/repos/github/fludio/doctrine-filter/badge.svg?branch=master)](https://coveralls.io/github/fludio/doctrine-filter?branch=master)
[![Build Status](https://travis-ci.org/fludio/doctrine-filter.svg?branch=master)](https://travis-ci.org/fludio/doctrine-filter)

## Installation

...

## Useage

To create a new filter, implement the `FilterInterface`.

``` php

use Fludio\DoctrineFilter\FilterBuilder;
use Fludio\DoctrineFilter\FilterInterface;

class MyFilter implements FilterInterface
{
    public function buildFilter(FilterBuilder $builder)
    {
        // ...
    }
}
```

Now you can start defining your filter, by calling `$builder->add()`.


``` php
public function buildFilter(FilterBuilder $builder)
{
    $builder
        ->add('category', EqualFilterType::class)
        ->add('price_max', LessThanEqualFilterType::class);
}
```

To use the filter on your entity, the easiest solution is to create a custom repository and use the `EntityFilterTrait`.

``` php
use Doctrine\ORM\EntityRepository;
use Fludio\DoctrineFilter\Traits\EntityFilterTrait;

class MyRepository extends EntityRepository
{
    use EntityFilterTrait;
}
```

To perform the query, you can now call the `filter`.

```
$result = $this-em->getRepository(MyEntity::class)->filter(new MyFilter(), [
	'category' => 2,
	'price_max' => 80
]);
```

This method will return all entities with a category of 2 and a price that is less than or equal 80.

## Filter Types

### BetweenFilterType

### ClosureFilterType

### ComparableFilterType

### EqualFilterType

### GreaterThanEqualFilterType

### GreaterThanFilterType

### InFilterType

### InstanceOfFilterType

### LessThanEqualFilterType

### LessThanFilterType

### LikeFilterType

### NotEqualFilterType

### NotInFilterType

### OrderByType

## Todo

- [ ] Options
  - [ ] Case sensititvity
- [ ] Distinct
