# Dot Notation
> `This is just a proof-of-concept, so...`

## Remember
+ __DON'T__ use it in __production__
+ __DON'T__ manage large datasets *(200+ elements, __too__ deeply nested)* 
+ You need at least `PHP 5.5` to make it work - I don't support old things

## Installation
+ Grab it from GitHub
+ Add to your project
+ Include `lib/DotNotation/DotNotation.php`
+ Don't forget about the tests *(PHPUnit)* if you want to change the code (check `tests` directory and `phpunit.xml.dist` for more info

## Usage
Creating

```php
$dot = DotNotation::create ();
```
You can pass an array or another `DotNotation` object to `create` method if you want to load some data you need.
Or you can...

### Loading existing data
Note that `from` method will __override__ existing data.
If you want to append your data, check out __Merge__

```php 
$dot->from (['foo' => 'bar']); // from array
$dot->from ( DotNotation::create (['foo' => 'bar']) ); // from DotNotation instance
$dot->from ('your json', DotNotation::JSON); // from JSON
$dot->from ('your serialized php array', DotNotation::PHP_SERIALIZED); // from serialize()'d array
```

### Merging datasets

```php
$dot->merge (['secret' => 42]); // merge $dot dataset with an array
$dot->merge ( DotNotation::create (['secret' => 42]) ); // or with another instance of DotNotation
```

### Getting the whole dataset

```php
$allData = $dot->root ();
$allData = $dot->toArray (); // the same, but more readable I think 
```

### Want something different?

```php
$allDataInJson = $dot->to (DotNotation::JSON); // cool JSON format 
$allDataSerialized = $dot->to (DotNotation::PHP_SERIALIZED); // serialize()'d array 
```

### Read Only Mode



