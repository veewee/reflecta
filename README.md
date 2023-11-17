<div align="center">

## 🪞 REFLECTA  🪞
### Unleash the Power of Optics in your code

</div>

Welcome to Reflecta!

Transform your data like never before with this optics implementation – a set of sleek, intuitive tools designed to empower your code with the finesse of reflection, the precision of lenses, and the magic of Isomorphisms.
Say goodbye to the complexities, and hello to a world where building, accessing and manipulating your data structures becomes as effortless as a brushstroke on a canvas.

Dive into this Optics Toolkit, where coding meets artistry, and simplicity meets power.
It's not just about data – it's about orchestrating a symphony of possibilities.
Your data has never looked so good!

## Installation

Reflecta is installed via Composer.

```
composer require veewee/reflecta
```
> Requires PHP 8.1+

**Warning.** This package is primarily published to receive early feedback and for contributors, during this development phase we cannot guarantee the stability of the APIs, consider each release to contain breaking changes.

### Psalm support

This package is created with type-safety and error awareness in mind.

In order to have better type inference, this package comes shipped with a psalm plugin.
You can enable it by:

```
./vendor/bin/psalm-plugin enable 'VeeWee\Reflecta\Psalm\Plugin'
```
> Requires vimeo/psalm 5+

## Components

This package provides following components:

* [ArrayAccess](/docs/array-access.md): Helps you read from and write to arrays.
* [Iso](/docs/isomorphisms.md): Provides bidirectional transformations on your data.
* [Lens](/docs/lens.md): Separate your data from it's structure
* [Reflect](/docs/reflect.md): Helps you read from and write to objects in a runtime-safe context.


## Inspiration

This library was inspired by the following projects:

* [marcosh/lamphpda-optics](https://github.com/marcosh/lamphpda-optics)
* [fp-ts/optic](https://github.com/fp-ts/optic)
* [haskell lens](https://hackage.haskell.org/package/lens)
