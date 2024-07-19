# PHP Utility library <!-- omit in toc -->

Library with useful classes and methods.

# Table of Contents <!-- omit in toc -->

- [Requirements](#requirements)
- [Installation](#installation)
- [CDT](#cdt)
  - [get](#get)
  - [fromTimestamp](#fromtimestamp)
  - [fromDateTime](#fromdatetime)
  - [toMicrotime](#tomicrotime)
  - [toDateTime](#todatetime)
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

# CDT

CDT (Compressed Date and Time) is a way of storing date and time
including milliseconds.

```php 
use Torugo\Util\CDT\CDT;
```

## get

Returns a CDT from current date/time.

```php
$cdt = CDT::get(); // returns something like "SGVU9Z2WV"
```

## fromTimestamp

Generates a CDT from a timestamp or [microtime](https://www.php.net/manual/pt_BR/function.microtime.php).

```php
$cdt = CDT::fromTimestamp(416410245.1234); // returns "6VX4790YA"
```

## fromDateTime

Generates a CDT from a PHP DateTime object.

```php
$dateTime = \DateTime::createFromFormat("Y-m-d H:i:s.u", "2017-08-01 14:45:56.789");
$cdt = CDT::fromDateTime($dateTime); // returns "OU0H0K0LX"
```

## toMicrotime

Converts a CDT to a microtime (float) number.

```php
$micro = CDT::toMicrotime("6VX4790YA"); // returns 416410245.1234
```

## toDateTime

Converts a CDT to a PHP DateTime object.

```php
$dateTime = CDT::toDateTime("6VX4790YA"); // returns an instance of DateTime
```

# SemVer

Validates and compare semantic version numbers.
The version number must follow [semver.org rules](https://semver.org)

## Usage <!-- omit in toc -->

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
