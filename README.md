# Dot Notation
This is just a proof-of-concept, don't take it seriously. Please.

## Remember
+ __Don't__ use it in __production__
+ __Don't__ manage large datasets *(200+ elements, __too__ deeply nested)* 
+ You need at least `PHP 5.5` to make it work - I don't support old things

## Installation
+ Grab it
+ Add it to your project
+ Include `lib/DotNotation/DotNotation.php`
+ Don't forget about the unit-tests in case you want to modify some code (I used PHPUnit)

## Demo
`Dot notation` is so cool. Want to take a look? Let's do this! 

```php
$structure = [
    'foo'    => 'bar',
    'secret' => 42   ,
    'agent'  => [
        'code'       => '007' ,
        'sunglasses' => 'cool',
        'info' => [
            'first_name'  => 'James',
            'second_name' => 'Bond',
        ],
    ],
];
```

Imagine that you want to get James' second name. What would you do?


Would you just write `$secondName = $structure ['agent']['info']['second_name'];`? 


Honestly, it looks really ugly. What about `$secondName = $structure ['agent.info.second_name'];`?


__This one looks much better, doesn't it?__


We can go even further and also use dot notation for *removing, declaring and changing* values

```php 
// using the structure declared above
$dot = DotNotation::create ($structure);

// equals $dot ['foo']['bar'] = 42;
$dot ['foo.bar'] = 42; // we have just used that sexy dot notation

// everyone should see it!
echo $dot ['foo.bar']; 

$dot ['foo.bar'] = 43; // much better

unset ($dot ['foo.bar']); // the meaning of life has been lost

```

Interested now?

## Magic Explained

Class `DotNotation` implements `ArrayAccess` so you can use its instances as PHP arrays.


`DotNotation` will parse `foo.bar` , find the desired element in a data storage (which is unique to all `DotNotation` instances) and give you the result or throw an exception if something go wrong. 


As always, magic takes up way too many resources, so you shouldn't use `DotNotation` in production or/and with large datasets.

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
If you want to make`$dot`unchangeable, you should use `readOnly` method

```php
$dot->readOnly (); // returns the current state of $dot
$dot->readOnly (true); // makes $dot unchangeable, passing FALSE gives the opposite result
```

## License
`DotNotation` is licensed under __the MIT license__.


Check the `LICENSE` file for more information.
