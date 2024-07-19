# PHP Utility library <!-- omit in toc -->

Library with useful classes and methods.

# Table of Contents <!-- omit in toc -->

- [Requirements](#requirements)
- [Installation](#installation)
- [Classes](#classes)
  - [SemVer](#semver)
- [Contribute](#contribute)
- [License](#license)

# Requirements

- PHP 8+
- PHP mbstring extension installed and loaded.
- Composer 2+


# Installation

On terminal type:

```shell
composer require torugo/util
```

Or add to your require list on composer.json file:

```json
{
    "require": {
        "torugo/util": "^0.1.0"
    }
}
```

# Classes

## SemVer

Validates and compare semantic version numbers.
The version number must follow [semver.org rules](https://semver.org)

### Usage <!-- omit in toc -->

```php
use Torugo\Util\SemVer\SemVer;

$version = new SemVer("1.0.0");

$version->compareTo("1.0.0");      // returns VersionComparison::Equal
$version->compareTo("1.0.1");      // returns VersionComparison::Smaller
$version->compareTo("1.0.0-beta"); // returns VersionComparison::Bigger
```

# Contribute

It is currently not open to contributions, I intend to make it available as soon as possible.

# License

This library is licensed under the MIT License - see the LICENSE file for details.
