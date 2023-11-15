# 🚀 Array Alchemy

Elevate your array manipulation game.
Unleash the power of arrays with tools that turn the mundane into the extraordinary.

## Functions

This package provides following functions for dealing with arrays.

#### index_get

Detects the value of an array at a given index.
The index could either be a number or a string.
If the index is not available inside the array, an `ArrayAccessException` exception is triggered! 

```php
use VeeWee\Reflecta\ArrayAccess\Exception\ArrayAccessException;
use function VeeWee\Reflecta\ArrayAccess\index_get;

try {
    $value = index_get($yourArray, $indexNumberOrName);
} catch (ArrayAccessException $e) {
    // Deal with it
}
```

#### index_set

Immutably saves a new value at a given index inside an array.
The index could either be a number or a string.
If the index is not available inside the array, a new index will be added.

```php
use function VeeWee\Reflecta\ArrayAccess\index_set;

$yourNewArray = index_set($yourOldArray, $indexNumberOrName, $newValueAtIndex);
```
