# ArrayObject

The purpose is to make the array easier to work with.  
Inspired by how arrays work in JavaScript.  
An array is wrapped in an object which methods maps to the built-in array functions.  
This was written as an excercise.

# Object array-functions

PHP's `array_<function>(…)` is accesable through `$arrObj-><function>(…)`.  
Most functions work as is, but some may need some custom implementations.

## Usage

```php
$arr = new ArrObj();

$arr[] = 42;

echo $arr->length; // 1
echo $arr[0]; // 42

$last = $arr->pop();

echo $arr->length; // 0
echo $last; // 42

// Expose the inner array by invoking the object
print_r($arr());

// Set the inner array, after creation
$arr([1, 2, 3]);
```
See tests for more examples.


### Chainable methods
```php
$arr = new ArrObj([1, 2, 3, 4, 5, 6]);

$squares = $arr->filter(function ($i) {
  return $i < 4;
})->map(function ($i) {
  return $i * $i;
});

print_r($squares);
/*
Result:
Array
(
    [0] => 1
    [1] => 4
    [2] => 9
)
*/
```
