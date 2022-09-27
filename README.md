# Doctrine-Filter blabla

## Installation

```bash
composer require queo/doctrine-filter
```

## Usage

If you would like to use the filter, create a new class and implement the `FilterInterface`.

```php
use Queo\DoctrineFilter\FilterBuilder;
use Queo\DoctrineFilter\FilterInterface;

class MyFilter implements FilterInterface
{
    public function buildFilter(FilterBuilder $builder)
    {
        // ...
    }
}
```

Now you can start defining your filter, by calling `$builder->add($name, $type)`.

```php
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

```php
use Doctrine\ORM\EntityRepository;
use Queo\DoctrineFilter\Traits\EntityFilterTrait;

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

## Ordering results

You also have the option to specify the order of the result by using the `orderBy` method. The first argument is the name of the filter, the second argument defines a possible default sorting. You can pass in `'ASC'` or `'DESC'`. Note that after setting the default values, you won't be able to pass a value to the filter. If you need the ability to define the ordering by the search params, pass in `null` as the second argument. 


## Filter Types

### BetweenFilterType

This filter can be used for ranges and will expose two search keys that you can use.

```php
use Queo\DoctrineFilter\Type\BetweenFilterType;

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

| Option              | Description                         | Default |
|---------------------|-------------------------------------|---------|
| lower_bound_suffix  | The suffix of the lower bound       | 'since' |
| upper_bound_suffix  | The suffix of the upper bound       | 'until' |
| include_lower_bound | Should the lower bound be included? | true    |
| include_upper_bound | Should the upper bound be included? | true    |


### ClosureFilterType

### ComparableFilterType

### EqualFilterType

With this filter the database value has to be the same as the search value.

### GreaterThanEqualFilterType

The database value has to be greater than or equal to the search value.

### GreaterThanFilterType

The database value has to be greater than the search value.

### InFilterType

The database value has to be in the given search values.

### InstanceOfFilterType

If you use inheritance mapping, you can use this filter to return only specific entities. The search values has to be equal to the defined key in the DiscriminatorMap.

### LessThanEqualFilterType

The database value has to be less than or equal to the search value.

### LessThanFilterType

The database value has to be less than the search value.

### LikeFilterType

Will perform a like query on the given field.

### NotEqualFilterType

The database value has to be different to the search value.

### NotInFilterType

The database value must not be in the given search values.

## Todo

- [ ] Options
  - [ ] Case sensititvity
  - [ ] Default value
- [ ] Distinct
- [ ] InstanceOf for multiple entities
