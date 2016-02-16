# Doctrine-Filter

[![Build Status](https://travis-ci.org/fludio/doctrine-filter.svg?branch=master)](https://travis-ci.org/fludio/doctrine-filter)
[![Coverage Status](https://coveralls.io/repos/github/fludio/doctrine-filter/badge.svg?branch=master)](https://coveralls.io/github/fludio/doctrine-filter?branch=master)

## Installation

```
composer require fludio/doctrine-filter
```

## Useage

If you would like to use the filter, create a new class and implement the `FilterInterface`.

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

Now you can start defining your filter, by calling `$builder->add($name, $type)`.

``` php
public function buildFilter(FilterBuilder $builder)
{
    $builder
        ->add('category', EqualFilterType::class)
        ->add('price_max', LessThanEqualFilterType::class)
        ->orderBy('price', 'DESC);
}
```

The name is the key by which the filter will be used. So if you would like to query your entity on the category, you will have to make sure to provide an array that has a key of `category`.

There are several different filter types that you can use. Have a look at the list below.

To use the filter on your entity, the easiest solution is to create a custom repository and use the `EntityFilterTrait`.

``` php
use Doctrine\ORM\EntityRepository;
use Fludio\DoctrineFilter\Traits\EntityFilterTrait;

class MyRepository extends EntityRepository
{
    use EntityFilterTrait;
}
```

To perform the query, you can now call the newly added `filter` method.

```
$result = $em->getRepository(MyEntity::class)->filter(new MyFilter(), [
	'category' => 2,
	'price_max' => 80
]);
```

This method will return all entities with a category of 2 and a price that is less than or equal to 80.

## Filter Types

### BetweenFilterType

This filter can be used for ranges and will expose two search keys that you can use.

``` php
use Fludio\DoctrineFilter\Type\BetweenFilterType;

$builder
	->add('price', BetweenFilterType::class, [
		'lower_bound_suffix' => 'from',
		'upper_bound_suffix' => 'to',
	]);
	
//...

$em->getRepository(MyEntity::class)->filter(new MyFilter(), [
	'price_from' => 40,
	'price_to' => 80
]); 
```

| Option                | Description                         | Default  |
| --------------------- | ----------------------------------- | -------- |
| lower_bound_suffix    | The suffix of the lower bound       | 'since'  |
| upper_bound_suffix    | The suffix of the upper bound       | 'until'  |
| include_lower_bound   | Should the lower bound be included? | true     |
| include_upper_bound   | Should the upper bound be included? | true     |


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
  - [ ] Default value
- [ ] Distinct
