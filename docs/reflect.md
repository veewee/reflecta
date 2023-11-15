# 🧲 Magnetic Reflections:

Illuminate your code's inner world with our reflection tools.
Peek into the heart of your data structures effortlessly, revealing the hidden gems that propel your applications forward.

This component provides runtime-safe reflections on objects.

## Functions

This package provides following functions for dealing with objects.

#### instantiate

This function instantiates a new object of the provided type by bypassing the constructor.

```php
use VeeWee\Reflecta\ArrayAccess\Exception\ArrayAccessException;
use function VeeWee\Reflecta\Reflect\instantiate;

try {
    $yourObject = instantiate(YourObject::class);
} catch (UnreflectableException) {
    // Deal with it
}
```

#### properties_get

Detects all values of all properties for a given object.
The properties can have any visibility.
If the object is not reflectable, an `UnreflectableException` exception is triggered!
The result of this function is of type: `array<string, mixed>`.

```php
use VeeWee\Reflecta\ArrayAccess\Exception\ArrayAccessException;
use function VeeWee\Reflecta\Reflect\properties_get;

try {
    $aDictOfProperties = properties_get($yourObject, $theProperty);
} catch (UnreflectableException) {
    // Deal with it
}
```

#### properties_set

Immutably saves the new values at the provided property locations inside a given object.
The values is a `array<string, mixed>` that takes the name of the property as key and the new property value as value.
If the property is not available on the object, an `UnreflectableException` will be thrown.

```php
use VeeWee\Reflecta\Exception\CloneException;
use function VeeWee\Reflecta\Reflect\properties_set;

try {
    $yourNewObject = properties_set($yourOldObject, $newValuesDict);
} catch (UnreflectableException | CloneException) {
    // Deal with it
}
```

#### property_get

Detects the value of a property for a given object.
The property could have any visibility.
If the property is not available inside the object, an `UnreflectableException` exception is triggered!
If all stars align, the result of this function gets inferred by psalm based on the provided named object and literal property.


```php
use VeeWee\Reflecta\ArrayAccess\Exception\ArrayAccessException;
use function VeeWee\Reflecta\Reflect\property_get;

try {
    $value = property_get($yourObject, $theProperty);
} catch (UnreflectableException) {
    // Deal with it
}
```

#### property_set

Immutably saves a new value at a specific property inside a given object.
If the property is not available on the object, an `UnreflectableException` will be thrown.

```php
use VeeWee\Reflecta\Exception\CloneException;
use function VeeWee\Reflecta\Reflect\property_set;

try {
    $yourNewObject = property_set($yourOldObject, $theProperty, $newValueForProp);
} catch (UnreflectableException | CloneException) {
    // Deal with it
}
```
