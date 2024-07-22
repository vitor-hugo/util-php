# PHP Utility library <!-- omit in toc -->

Library with useful classes and methods.

# Table of Contents <!-- omit in toc -->

- [Requirements](#requirements)
- [Installation](#installation)
- [Utilities](#utilities)
  - [CDT (Compressed Date and Time)](#cdt-compressed-date-and-time)
    - [get](#get)
    - [fromTimestamp](#fromtimestamp)
    - [fromDateTime](#fromdatetime)
    - [toMicrotime](#tomicrotime)
    - [toDateTime](#todatetime)
  - [DateWriter](#datewriter)
    - [Format options](#format-options)
    - [Internationalization](#internationalization)
    - [Usage](#usage)
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

# Utilities

## CDT (Compressed Date and Time)

CDT is a way of storing date and time
including milliseconds.

```php 
use Torugo\Util\CDT\CDT;
```

### get

Returns a CDT from current date/time.

```php
$cdt = CDT::get(); // returns something like "SGVU9Z2WV"
```

### fromTimestamp

Generates a CDT from a timestamp or [microtime](https://www.php.net/manual/pt_BR/function.microtime.php).

```php
$cdt = CDT::fromTimestamp(416410245.1234); // returns "6VX4790YA"
```

### fromDateTime

Generates a CDT from a PHP DateTime object.

```php
$dateTime = \DateTime::createFromFormat("Y-m-d H:i:s.u", "2017-08-01 14:45:56.789");
$cdt = CDT::fromDateTime($dateTime); // returns "OU0H0K0LX"
```

### toMicrotime

Converts a CDT to a microtime (float) number.

```php
$micro = CDT::toMicrotime("6VX4790YA"); // returns 416410245.1234
```

### toDateTime

Converts a CDT to a PHP DateTime object.

```php
$dateTime = CDT::toDateTime("6VX4790YA"); // returns an instance of DateTime
```

## DateWriter

Transforms DateTime objects to written date/time.

```php
$dw = new DateWriter( \DateTime $dt, string $language );
$dw->write(string $format);
```

### Format options

Accepts all PHP [`DateTimeInterface::format`](https://www.php.net/manual/en/datetime.format.php) characters.

Everything in brackets (`[]`) will not change in any way.

By default, all names of months and days of the week are defined as title case,
to transform the cases use the markings:

- `*{ ... }` To convert to uppercase.
- `%{ ... }` To convert to lowercase.

### Internationalization

Languages available to write the names of months and days of the week.

| Option | Language   | Examples                                           |
| ------ | ---------- | -------------------------------------------------- |
| 'de'   | Deutsch    | "Januar" ... "Dezember" / "Montag" ... "Sonntag"   |
| 'en'   | English    | "January" ... "December" / "sunday" ... "saturday" |
| 'es'   | Spanish    | "Enero" ... "Diciembre" / "Domingo" ... "Sábado"   |
| 'fr'   | French     | "Janvier" ... "Décembre" / "Dimanche" ... "Samedi" |
| 'pt'   | Portuguese | "Janeiro" ... "Dezembro" / "Domingo" ... "Sábado"  |

### Usage

```php
use Torugo\Util\DateWriter\DateWriter;

$dateTime = \DateTime::createFromFormat("Y-m-d H:i:s", "2017-08-01 15:30:45");
$dw = new DateWriter($dateTime, "pt");

$dw->write("[São Paulo,] j [de] %{F} [de] Y");
// São Paulo, 1 de agosto de 2017

$dw->write("*{l}");
// TERÇA-FEIRA
```

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
