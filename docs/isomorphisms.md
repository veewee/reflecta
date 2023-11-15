# 🔄 Isomorphic Magic

Seamlessly navigate between different data representations with our Isomorphisms.
Experience the enchantment of transforming your data effortlessly, as if conducting a symphony of bits and bytes.

An isomorphism, in the world of optics, is like a universal translator for your data.
It's the secret sauce that allows you to seamlessly switch between different representations of information without losing any meaning.
Imagine effortlessly translating a complex concept from one language to another, each version capturing the essence perfectly.
Isomorphisms do just that for your data, providing a smooth and reversible way to navigate between various structures,
ensuring that your information remains intact and coherent, regardless of its form.

Let's dive into a small example:

# Iso

```php
use VeeWee\Reflecta\Iso\Iso;

$base64 = new Iso(
    to: base64_encode(...),
    from: base64_decode(...),
);

$data = 'hello world';
$encoded = $base64->to($data);
// > "aGVsbG8gd29ybGQ="
$actual = $base64->from($encoded);
// > "hello world"

assert($actual === $data);
```

This example provides an isomorphism for encoding a value to base64 or decoding from a base64 value.
The result of the `to()` method will always be revertable by using the `from()` method.

## Composability

You can compose many ISOs into one bigger ISO:

```php
use VeeWee\Reflecta\Iso\Iso;

$base64 = new Iso(
    base64_encode(...),
    base64_decode(...),
);

$commaSeparated = new Iso(
    static fn (array $keywords): string => join(',', $keywords),
    static fn (string $keywords): array => explode(',', $keywords)
);

$commaSeparatedBase64 = $commaSeparated->compose($base64);

$data = ['hello' ,'world'];
$encoded = $commaSeparatedBase64->to($data);
// > ["hello", "world] -> "hello,world" -> aGVsbG8sd29ybGQ=
$actual = $commaSeparatedBase64->from($encoded);
// > aGVsbG8sd29ybGQ= -> "hello,world", ["hello", "world"]

assert($actual === $data);
```


## Functions

#### compose

This function is able to compose multiple isomorphisms into a new one.
Check the chapter [composability](#composability) for more information.
A psalm plugin is available that validates if the types of the ISOs are composable.

```php
use function VeeWee\Reflecta\Iso\compose;

$commaSeparatedBase64 = compose(
    $commaSeparated,
    $base64,
    // ...others
);
```

#### object_data

This function will create an Iso that can access object data.

* **To** will transform an `array<string, mixed>` and use this array to fill a new instance of the provided named object.
* **From** will transform an instance of the provided named object into a `array<string, mixed>` that contains a full list of all properties with their values.

```php
use function VeeWee\Reflecta\Iso\object_data;

class Item {
    public string $value;
}

$itemData = object_data(Item::class);

$data = [
    'value' => 'hello'
];
$itemInstance = $itemData->from($data);
$actualData = $itemData->to($item);

assert($data === $actualData);
```
