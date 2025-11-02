<!--- BEGIN HEADER -->
# Changelog

All notable changes to this project will be documented in this file.
<!--- END HEADER -->

## [2.1.0](https://github.com/CuyZ/Valinor-Bundle/compare/2.0.0...2.1.0) (2025-11-02)

### Features

* Add support for PHP 8.5 ([b4808a](https://github.com/CuyZ/Valinor-Bundle/commit/b4808ac18f83949ccc55cf2e4c5713ef4109bd39))

### Bug Fixes

* Automatically register cache for normalizer builder ([583b3f](https://github.com/CuyZ/Valinor-Bundle/commit/583b3fe9878b7b471f43b532497dc388a9551ce7))

---

## [2.0.0](https://github.com/CuyZ/Valinor-Bundle/compare/1.0.0...2.0.0) (2025-06-27)

### ⚠ BREAKING CHANGES

* Make bundle compatible with Valinor 2.0 ([6361a7](https://github.com/CuyZ/Valinor-Bundle/commit/6361a78e84d63c3683fe3c16471a2f906f59a8b9))

---

## [1.0.0](https://github.com/CuyZ/Valinor-Bundle/compare/0.4.1...1.0.0) (2025-05-29)

First stable release 🎉

---

Note that this release also includes a breaking change: the feature that allowed
attributes to configure a `TreeMapper` directly during the injection has been
removed.

After some thoughts, this implementation was a bad idea that led to more
complexity in the bundle code base, for something that should anyway be done
differently.

There will be no replacement for this feature, and code that used it should
instead either inject an instance of `MapperBuilder` or use the
`MapperBuilderConfigurator` interface.

### ⚠ BREAKING CHANGES

* Remove `MapperBuilderConfiguratorAttribute` support ([ddcf2f](https://github.com/CuyZ/Valinor-Bundle/commit/ddcf2fbb74e5b4803fc3c3844c59b3412818b6c2))

### Features

* Register `ArrayNormalizer` and `JsonNormalizer` as services ([dcfd1c](https://github.com/CuyZ/Valinor-Bundle/commit/dcfd1c3339690d3c216eef33c1f64b130bdf75be))

---

## [0.4.1](https://github.com/CuyZ/Valinor-Bundle/compare/0.4.0...0.4.1) (2024-11-24)

### Bug Fixes

* Explicitly mark service parameter as nullable ([0232d9](https://github.com/CuyZ/Valinor-Bundle/commit/0232d9f5869e018ecd23da351e8daa3debf1fd02))

---

## [0.4.0](https://github.com/CuyZ/Valinor-Bundle/compare/0.3.0...0.4.0) (2024-11-21)

### Features

* Add support for PHP 8.4 ([52fe0a](https://github.com/CuyZ/Valinor-Bundle/commit/52fe0a5d14d01a556b4bc6bebbe91bb696e4f15b))

---

## [0.3.0](https://github.com/CuyZ/Valinor-Bundle/compare/0.2.3...0.3.0) (2024-05-27)

### Features

* Add alias for `MapperBuilder` ([23e8c6](https://github.com/CuyZ/Valinor-Bundle/commit/23e8c6800867918034bd85a9d901bf6414d2b43e))

### Bug Fixes

* Solve deprecation message regarding warm-up class ([2d6bba](https://github.com/CuyZ/Valinor-Bundle/commit/2d6bba8538dd47bb569b3b5a2dd10c0f363cb2d8))

### Other

* Drop support for PHP 8.0 ([d631b2](https://github.com/CuyZ/Valinor-Bundle/commit/d631b22bca9d9b66076707234b03815436b89eaa))

---

## [0.2.3](https://github.com/CuyZ/Valinor-Bundle/compare/0.2.2...0.2.3) (2024-01-13)

### Bug Fixes

* Set class name in compiler pass definition ([3ef094](https://github.com/CuyZ/Valinor-Bundle/commit/3ef094a975d540b9a038a17a1811a08827c2ad75))

### Other

* Watch files in test environment by default ([2abfbb](https://github.com/CuyZ/Valinor-Bundle/commit/2abfbb0c9c269b3e630c3f217edb89e2ff48b8a3))

---

## [0.2.2](https://github.com/CuyZ/Valinor-Bundle/compare/0.2.1...0.2.2) (2023-10-11)

### Bug Fixes

* Correctly fetch Kernel environment in services configuration ([882778](https://github.com/CuyZ/Valinor-Bundle/commit/882778f3c5d376925794e3e717787daaa0e95872))

---

## [0.2.1](https://github.com/CuyZ/Valinor-Bundle/compare/0.2.0...0.2.1) (2023-09-08)

### Bug Fixes

* Disable injection autowiring for `WarmupForMapper` attribute ([c5a984](https://github.com/CuyZ/Valinor-Bundle/commit/c5a98407b85289b2883edd97e49d8cd869bb2922))

---

## [0.2.0](https://github.com/CuyZ/Valinor-Bundle/compare/0.1.0...v0.2.0) (2023-08-25)

### Features

* Add support for PHP 8.3 ([0c0735](https://github.com/CuyZ/Valinor-Bundle/commit/0c073572cbc05035240ed95e99b653302d284a05))

### Bug Fixes

* Disable injection autowiring for `WarmupForMapper` attribute ([2dc2b9](https://github.com/CuyZ/Valinor-Bundle/commit/2dc2b9301745a2633202779bf66325bd559c895f))
* Run cache warmup even if no class was provided ([0fa125](https://github.com/CuyZ/Valinor-Bundle/commit/0fa125b52512c56ff93ed39191c2446e8e6b6f98))

---

## [0.1.0](https://github.com/CuyZ/Valinor-Bundle/commit/4b2ae168f3b3332043a21c34683fd22bac33803e) (2023-08-07)

Initial release 🎉
