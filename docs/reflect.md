# 🧲 Magnetic Reflections:

Illuminate your code's inner world with our reflection tools.
Peek into the heart of your data structures effortlessly, revealing the hidden gems that propel your applications forward.

This component provides runtime-safe reflections on objects.

## Functions

This package provides following functions for dealing with objects.

#### class_attributes

Detects all attributes at the class level of the given className that match the optionally provided argument type (or super-type).
If the class is not reflectable or there is an error instantiating any argument, an `UnreflectableException` exception is triggered!
The result of this function is of type: `list<object>`. However, if you provide an argument name: psalm will know the type of the attribute.

```php
use function VeeWee\Reflecta\Reflect\class_attributes;

try {
    $allAttributes = class_attributes(YourClass::name);
    $allAttributesOfType = class_attributes(YourClass::name, \YourAttributeType::class);
    $allAttributesOfType = class_attributes(YourClass::name, \YourAbstractBaseType::class);
} catch (UnreflectableException) {
    // Deal with it
}
```

#### class_has_attribute

Checks if the class contains an attribute of given type (or super-type).
If the class is not reflectable, an `UnreflectableException` exception is triggered!

```php
use function VeeWee\Reflecta\Reflect\object_has_attribute;

try {
    $hasAttribute = class_has_attribute(YourClass::name, \YourAttributeType::class);
    $hasAttributeThatImplementsBaseType = class_has_attribute(YourClass::name, \YourAbstractBaseType::class);
} catch (UnreflectableException) {
    // Deal with it
}
```

#### class_is_dynamic

Checks if the provided class is considered a safe dynamic object that implements `AllowDynamicProperties`.
Since this property was only added in PHP 8.1, all older versions will always return `true` and allow adding dynamic properties to that class.
If the object is not reflectable, an `UnreflectableException` exception is triggered!

```php
use function VeeWee\Reflecta\Reflect\class_is_dynamic;

try {
    $isDynamic = class_is_dynamic(new stdClass());
    $isDynamic = class_is_dynamic(new #[\AllowDynamicProperties] class() {});
    $isNotDynamic = class_is_dynamic(new class() {});
} catch (UnreflectableException) {
    // Deal with it
}
```

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

#### object_attributes

Detects all attributes at the class level of the given object that match the optionally provided argument type (or super-type).
If the object is not reflectable or there is an error instantiating any argument, an `UnreflectableException` exception is triggered!
The result of this function is of type: `list<object>`. However, if you provide an argument name: psalm will know the type of the attribute.

```php
use function VeeWee\Reflecta\Reflect\object_attributes;

try {
    $allAttributes = object_attributes($yourObject);
    $allAttributesOfType = object_attributes($yourObject, \YourAttributeType::class);
    $allAttributesOfType = object_attributes($yourObject, \YourAbstractBaseType::class);
} catch (UnreflectableException) {
    // Deal with it
}
```

#### object_has_attribute

Checks if the object contains an attribute of given type (or super-type).
If the object is not reflectable, an `UnreflectableException` exception is triggered!

```php
use function VeeWee\Reflecta\Reflect\object_has_attribute;

try {
    $hasAttribute = object_has_attribute($yourObject, \YourAttributeType::class);
    $hasAttributeThatImplementsBaseType = object_has_attribute($yourObject, \YourAbstractBaseType::class);
} catch (UnreflectableException) {
    // Deal with it
}
```

#### object_is_dynamic

Checks if the provided object is considered a safe dynamic object that implements `AllowDynamicProperties`.
Since this property was only added in PHP 8.1, all older versions will always return `true` and allow adding dynamic properties to your object.
If the object is not reflectable, an `UnreflectableException` exception is triggered!

```php
use function VeeWee\Reflecta\Reflect\object_is_dynamic;

try {
    $isDynamic = object_is_dynamic(new stdClass());
    $isDynamic = object_is_dynamic(new #[\AllowDynamicProperties] class() {});
    $isNotDynamic = object_is_dynamic(new class() {});
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
