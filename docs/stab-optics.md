# 🧬 STAB optics — the four-letter dance

If you've poked at the type signatures of `Iso` or `Lens` and thought
*"why on earth are there four type parameters?"*, this page is for you.

You don't need any prior optics or category-theory background.
We'll start from a concrete problem, see why two type parameters aren't
enough, and arrive at the four-letter shape `Iso<S, T, A, B>` /
`Lens<S, T, A, B>` (called **STAB**, pronounced like the word).

By the end you'll know:

* what each of `S`, `T`, `A`, `B` means
* when you actually need all four (and when you can just repeat two)
* how `compose` lines up the four slots
* a mnemonic to keep it all straight

---

## The problem

Suppose you have a `Person`:

```php
class Person {
    public string $name;
}
```

You want a lens that lets you read and write the person's `name`.
That's the bread-and-butter Lens, and you write it like this:

```php
use VeeWee\Reflecta\Lens\Lens;

$nameLens = new Lens(
    get: fn (Person $p): string => $p->name,
    set: function (Person $p, string $newName): Person {
        $new = clone $p;
        $new->name = $newName;
        return $new;
    },
);

$alice    = new Person(); $alice->name = 'Alice';
$alice2   = $nameLens->set($alice, 'Alice the Great');
// > Person { name: "Alice the Great" }
```

So far so good. In terms of *types*, this lens lives between
**Person** (the whole thing) and **string** (the focused detail).
You might naturally write that as `Lens<Person, string>`: two type
parameters, "the whole" and "the focus". That's how plenty of optics
tutorials introduce it, and for this example it works fine.

So why does Reflecta's `Lens` take **four** type parameters?

---

## A slightly trickier example

Let's anonymize people. The rule: take a `Person`, replace their name
with a `HashedName`, and produce an `AnonymizedPerson`.

```php
final class HashedName {
    public function __construct(public string $hash) {}
}

final class AnonymizedPerson {
    public function __construct(public HashedName $name) {}
}
```

We want a lens that, when you `set` a `HashedName` on a `Person`,
hands you back an `AnonymizedPerson` instead.

In other words, *writing through the lens changes what type the
whole thing is*.

```php
$anonymizingLens = new Lens(
    get: fn (Person $p): string => $p->name,
    set: fn (Person $p, HashedName $hashed): AnonymizedPerson
        => new AnonymizedPerson($hashed),
);

$alice           = new Person(); $alice->name = 'Alice';
$hashed          = new HashedName(sha1('Alice'));
$anonymousAlice  = $anonymizingLens->set($alice, $hashed);
// > AnonymizedPerson { name: HashedName { hash: "..." } }
```

Now look at the four types floating around:

| Role | Type |
|---|---|
| The thing we read **from** | `Person` |
| The thing we get **out** when reading | `string` (the original name) |
| The thing we supply when writing | `HashedName` |
| The thing we produce when writing | `AnonymizedPerson` |

Four roles. Two type parameters can't capture this; they can only
express "the whole" and "the focus", treating both directions the same.

So: four parameters it is.

---

## The four letters

Reflecta names them `S`, `T`, `A`, `B`, following the convention from
Haskell's `lens` library. Pretty much every other optics library you'll
find uses the same names.

| Slot | Stands for | Role |
|---|---|---|
| `S` | **S**ource (input) | The type you read **from** |
| `T` | **T**arget (output) | The type you produce when you write **back** |
| `A` | **A**tom (output) | The focused value you extract from `S` |
| `B` | **B**uild (input) | The focused value you supply to produce `T` |

So the anonymizing lens above has type:

```
Lens<Person, AnonymizedPerson, string, HashedName>
//   ─ S ─   ──── T ────       ─ A ─   ─── B ───
```

Read it as: *"a lens between (Person, AnonymizedPerson) and (string, HashedName)"*.

The method signatures fall out naturally:

```php
// Lens<S, T, A, B>
$lens->get(S $s): A           // read from S, get an A out
$lens->set(S $s, B $b): T     // read from S, write a B, get T back
```

The `Iso` shape is the same idea, just bidirectional:

```php
// Iso<S, T, A, B>
$iso->to(S $s): A             // forward
$iso->from(B $b): T           // backward
```

---

## The 95% case: when S = T and A = B

For the boring "I just want to read and write the name on a Person"
lens from the very first example, there's no type change. The whole
is always a `Person`. The focus is always a `string`. So:

* `S = Person`, `T = Person` (you read a Person, you write back a Person)
* `A = string`, `B = string` (the focus is a string both ways)

You write it like this:

```php
/** @var Lens<Person, Person, string, string> $nameLens */
$nameLens = new Lens(
    get: fn (Person $p): string => $p->name,
    set: function (Person $p, string $newName): Person {
        $new = clone $p;
        $new->name = $newName;
        return $new;
    },
);
```

Yes, you're repeating yourself. That's fine. Other optics libraries
(Monocle in Scala, Arrow in Kotlin, monocle-ts in TypeScript,
LanguageExt in C#) all do it the same way: one `Lens` shape with four
slots, and you repeat the same type twice when you don't need to change
types.

The mental shortcut: **"when in doubt, repeat the two types."** That
covers the 95% case with no head-scratching.

> **Heads up:** Psalm doesn't yet support template default values, so
> you can't write `Lens<Person, string>` and have it expand to
> `Lens<Person, Person, string, string>` automatically. If/when Psalm
> adds that, we'll surface it here.

---

## When you actually want all four

Whenever writing through the optic *changes the type of the whole*,
you need the full STAB shape. A few real-world cases:

* **Anonymization / pseudonymization.** Replace a `string $name` with
  a `HashedName` and end up with an `AnonymizedPerson`.
* **Unit conversions where the wrapper changes.** Replace a `Meters`
  inside a `MeasurementInMeters` and produce a `MeasurementInFeet`.
* **Phase changes in a builder.** Replace a `Draft` payload inside an
  `Order<Draft>` and produce an `Order<Confirmed>`.

```php
// Lens that pseudonymizes the focus and the wrapper at the same time
/** @var Lens<Person, AnonymizedPerson, string, HashedName> $anonymize */
$anonymize = new Lens(
    get: fn (Person $p): string => $p->name,
    set: fn (Person $p, HashedName $hashed): AnonymizedPerson
        => new AnonymizedPerson($hashed),
);

$alice          = new Person();          $alice->name = 'Alice';
$hashed         = new HashedName('…');
$anonymousAlice = $anonymize->set($alice, $hashed);
// > AnonymizedPerson { name: HashedName { hash: "…" } }
```

If you only ever use the lens for `get`, the type-changing slots
(`T` and `B`) are dormant. They sit there equal to whatever makes the
writer happy.

---

## Compose: the four slots line up

The `compose` operation chains two optics together. With STAB it
expresses what people actually mean: the **focus** of the first optic
must match the **whole** of the second.

```
   Lens<S, T, A, B>          ∘          Lens<A, B, C, D>
   ─────────────────                     ─────────────────
   reads S, focuses A                    reads A, focuses C
   writes B back, produces T             writes D back, produces B

   ⇩ compose

                  Lens<S, T, C, D>
                  ─────────────────
                  reads S, focuses C
                  writes D back, produces T
```

The inner pair `(A, B)` of the first lens is exactly the outer pair
`(S, T)` of the second. The composed lens keeps the outer pair of the
first and the inner pair of the second.

Concrete monomorphic example:

```php
use function VeeWee\Reflecta\Lens\compose;
use function VeeWee\Reflecta\Lens\property;

class Person { public Hat $hat; }
class Hat    { public string $color; }

/** @var Lens<Person, Person, Hat, Hat> $hatLens */
$hatLens = property('hat');

/** @var Lens<Hat, Hat, string, string> $colorLens */
$colorLens = property('color');

/** @var Lens<Person, Person, string, string> $hatColorLens */
$hatColorLens = compose($hatLens, $colorLens);

$person = new Person();
$person->hat = new Hat();
$person->hat->color = 'green';

$hatColorLens->get($person);              // > "green"
$hatColorLens->set($person, 'red');       // > Person { hat: { color: "red" } }
```

Because every slot is invariant, Psalm will flag a real mistake at the
compose boundary. For example, if `colorLens` focused on `int` but you
tried to compose it after a `hatLens` that focuses on `Hat`, the
`(A, B)` of one and `(S, T)` of the other wouldn't line up and you'd
get an `InvalidArgument` from Psalm. No plugin involvement, no runtime
check, just standard type inference doing its job.

---

## A few derived shapes worth knowing

A handful of helpers in the library collapse or swap the STAB slots in
specific ways. You don't have to memorize these; they make sense once
the four-slot model has clicked.

#### `Iso::inverse()`

Flipping an iso swaps the forward and backward directions. The
`(S, T)` pair and the `(A, B)` pair trade places, and inside each pair
the two slots also swap:

```
Iso<S, T, A, B>::inverse(): Iso<B, A, T, S>
```

If `$base64` is an `Iso<string, string, EncodedString, EncodedString>`,
then `$base64->inverse()` is an
`Iso<EncodedString, EncodedString, string, string>`. Same data, going
the other way.

#### `Lens::readonly(…)` / `read_only(…)`

A read-only lens can't be written through, so it can't change types
either. Both `T = S` and `B = A` are forced:

```
read_only(LensInterface<S, T, A, B>): Lens<S, S, A, A>
```

#### `optional(…)` / `Lens::optional()`

Wrapping a lens with `optional` says "anything in the pipeline might be
missing". Every slot becomes nullable, so the wrapper can short-circuit
to `null` on either direction:

```
optional(LensInterface<S, T, A, B>): Lens<S|null, T|null, A|null, B|null>
```

---

## Cheat sheet

Stick this on a sticky note:

```
S = Source   — what you read FROM
T = Target   — what you write OUT to
A = Atom     — what you read OUT as the focus
B = Build    — what you write IN as the focus
```

Decision tree:

```
Need to write a lens / iso, and …

Q: does writing through it change the outer type?
   ├── no  → just repeat both types:  Lens<S, S, A, A>
   └── yes → use all four:            Lens<S, T, A, B>
                                      with T ≠ S and/or B ≠ A
```

---

## Footnote: a note on variance

> You can skip this section. It only matters if you already know what
> "covariance" / "contravariance" mean from other type systems (Scala,
> TypeScript, C#) and were wondering how Reflecta declares its slots.

Reflecta splits the choice across two layers.

* **Concrete `Iso<S, T, A, B>` and `Lens<S, T, A, B>` are invariant on
  all four slots.** That's where the type safety lives: `new Iso(...)`,
  `$iso->compose($other)` between concrete optics, and the SA tests
  for compose boundary checking all rely on invariant unification. If
  you build optics and chain them, you get the full STAB type check.

* **`IsoInterface<S, T, A, B>` and `LensInterface<S, T, A, B>` are
  covariant on all four slots.** That's where the flexibility lives:
  an `Iso<Dog, Dog, string, string>` fits in a slot typed
  `IsoInterface<mixed, mixed, mixed, mixed>`, so you can keep
  heterogeneous optics in a single collection without losing the leaf
  types.

C# does the same split: `List<T>` is invariant; `IEnumerable<out T>`
is covariant. You pick which side of that trade by which type you
write in your signatures.

### When you need the interface covariance

Concrete example: you want to keep different-shaped optics in a single
map, looked up by name.

```php
/** @var array<string, IsoInterface<mixed, mixed, mixed, mixed>> $isos */
$isos = [];

/** @var Iso<Person, Person, string, string> $nameIso */
$nameIso = new Iso(/* … */);

/** @var Iso<int, int, string, string> $countIso */
$countIso = new Iso(/* … */);

$isos['name']  = $nameIso;   // OK: covariance widens to IsoInterface<mixed, …>
$isos['count'] = $countIso;  // OK
```

Without the interface covariance, those storage lines wouldn't
type-check: the invariant slots refuse to widen `Iso<Person, …>` to
`Iso<mixed, …>`. You'd be pushed to type everything as `mixed` from
the leaves up, and the precise types on `$nameIso` and `$countIso`
would be unusable in a shared collection.

### The cost: opt-in unsoundness

Interface covariance is unsound on input slots (`S` and `B`). What
breaks at runtime:

```php
/** @var IsoInterface<Animal, Animal, string, string> $animalIso */
$animalIso = $someDogIso;       // widens via covariance, Psalm allows it

$animalIso->to(new Cat());       // BOOM at runtime: the underlying iso wanted a Dog
```

Psalm won't catch that. The runtime will.

So: code that wants the strict guarantee writes concrete
`Iso<S, T, A, B>` / `Lens<S, T, A, B>` in its signatures. Code that
needs storage flexibility writes the interface. **You opt in to the
unsoundness by typing against the interface.**

In practice, the registries this covariance exists for don't exercise
the unsoundness anyway. They store concrete optics, look them up by
key, and deal in `mixed` at the retrieval boundary. The widening is
real; the runtime explosion only happens if a caller claims a
narrower type than the underlying optic actually supports.

---

## Where to go next

* [🔍 Lenses of Clarity](./lens.md): practical Lens guide, with
  constructors like `property`, `index`, `optional`, etc.
* [🔄 Isomorphic Magic](./isomorphisms.md): practical Iso guide,
  including `object_data` and `compose`.

If anything on this page is unclear, please [open an issue](https://github.com/veewee/reflecta/issues).
The goal here is "explained well enough that the next reader doesn't
need to read it twice".
