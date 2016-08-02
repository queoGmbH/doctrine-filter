---
layout: default
title: {{ site.name }}
---

# Doctrine-Filter

[![Build Status](https://travis-ci.org/bitecodes/doctrine-filter.svg?branch=master)](https://travis-ci.org/bitecodes/doctrine-filter)
[![Coverage Status](https://coveralls.io/repos/github/bitecodes/doctrine-filter/badge.svg?branch=master)](https://coveralls.io/github/bitecodes/doctrine-filter?branch=master)

## Content

- [Installation](#installation)
- [Usage](#usage)
- [Ordering Results](#ordering-results)
- [FilterTypes](#filter-types)

## Installation

```
composer require bitecodes/doctrine-filter
```

## Usage

If you would like to use the filter, the first thing you have to do is to create a new class for your filter and implement the `FilterInterface`.

``` php

use BiteCodes\DoctrineFilter\FilterBuilder;
use BiteCodes\DoctrineFilter\FilterInterface;

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

The first arguments is the key by which the filter will be activated. So if you would like to query your entity on the category, you will have to make sure to provide an array that has a key of `category` (see below).

There are several different filter types that you can use. [Have a look at the list below](#filter-types).

To use the filter on your entity, the easiest way is to create a custom repository and use the `EntityFilterTrait`.

``` php
use Doctrine\ORM\EntityRepository;
use BiteCodes\DoctrineFilter\Traits\EntityFilterTrait;

class MyRepository extends EntityRepository
{
    use EntityFilterTrait;
}
```

This repository should be used by your entity. To perform the query, you can now call the newly added `filter` method.

```
$result = $em->getRepository(MyEntity::class)->filter(new MyFilter(), [
  'category' => 2,
  'price_max' => 80
]);
```

This method will return all entities with a category of 2 and a price that is less than or equal to 80.

## Ordering results

You also have the option to specify the order of the result by using the `orderBy` method. The first argument is the name of the filter, the second argument defines a possible default sorting. You can pass in `'ASC'` or `'DESC'`. Note that after setting the default values, you won't be able to pass a value to the filter. If you need the ability to define the ordering by the search params, pass in `null` as the second argument. 

## Filter Types

- [BetweenFilterType](#betweenfiltertype)
- [ComparableFilterType](#comparablefiltertype)
- [EqualFilterType](#equalfiltertype)
- [GreaterThanFilterType](#greaterthanfiltertype--greaterthanequalfiltertype)
- [GreaterThanEqualFilterType](#greaterthanfiltertype--greaterthanequalfiltertype)
- [InFilterType](#infiltertype)
- [InstanceOfFilterType](#instanceoffiltertype)
- [LessThanFilterType](#lessthanfiltertype--lessthanequalfiltertype)
- [LessThanEqualFilterType](#lessthanfiltertype--lessthanequalfiltertype)
- [LikeFilterType](#likefiltertype)
- [NotEqualFilterType](#notequalfiltertype)
- [NotInFilterType](#notinfiltertype)

### BetweenFilterType

This filter can be used for ranges and will expose two search keys that you can use.

``` php
use BiteCodes\DoctrineFilter\Type\BetweenFilterType;

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

With this filter the database value has to be the same as the search value.

``` php
use BiteCodes\DoctrineFilter\Type\EqualFilterType;

$builder
  ->add('category', EqualFilterType::class);
  
//...

$em->getRepository(MyEntity::class)->filter(new MyFilter(), [
  'category' => 'health'
]); 
```

| Option                | Description                         | Default  |
| --------------------- | ----------------------------------- | -------- |
| case_sensitive        | Use case-sensitive search           | true     |

### GreaterThanFilterType / GreaterThanEqualFilterType

The database value has to be greater than (or equal) to the search value.

``` php
use BiteCodes\DoctrineFilter\Type\GreaterThanEqualFilterType;

$builder
  ->add('price', GreaterThanEqualFilterType::class);
  
//...

$em->getRepository(MyEntity::class)->filter(new MyFilter(), [
  'price' => 80
]); 
```

### InFilterType

The database value has to be in the given search values.

### InstanceOfFilterType

If you use inheritance mapping, you can use this filter to return only specific entities. The search values has to be equal to the defined key in the DiscriminatorMap.

``` php
use BiteCodes\DoctrineFilter\Type\InstanceOfFilterType;

$builder
  ->add('type', InstanceOfFilterType::class);
  
//...

$em->getRepository(Vehicle::class)->filter(new MyFilter(), [
  'type' => 'car'
]);
```

### LessThanFilterType / LessThanEqualFilterType

The database value has to be less than (or equal) to the search value.

``` php
use BiteCodes\DoctrineFilter\Type\LessThanEqualFilterType;

$builder
  ->add('price', LessThanEqualFilterType::class);
  
//...

$em->getRepository(MyEntity::class)->filter(new MyFilter(), [
  'price' => 80
]); 
```

### LikeFilterType

Will perform a like query on the given field.

``` php
use BiteCodes\DoctrineFilter\Type\LikeFilterType;

$builder
  ->add('color', LikeFilterType::class);
  
//...

$em->getRepository(Car::class)->filter(new MyFilter(), [
  'color' => 'blue'
]); 

# will return cars with the color blue, navy blue, royal blue, etc...
```

| Option                | Description                                            | Default  |
| --------------------- | ------------------------------------------------------ | -------- |
| starts_with           | The string will have to start with the given value     | false    |
| ends_with             | The string will have to end with the given value       | false    |

### NotEqualFilterType

The database value has to be different to the search value.

``` php
use BiteCodes\DoctrineFilter\Type\NotEqualFilterType;

$builder
  ->add('category', NotEqualFilterType::class);
  
//...

$em->getRepository(MyEntity::class)->filter(new MyFilter(), [
  'category' => 'health'
]); 

# will return all entities that are NOT in the health category 
```

### NotInFilterType

The database value must not be in the given search values.

