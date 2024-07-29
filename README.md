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
  - [TBase64 (url safe)](#tbase64-url-safe)
  - [TEncrypt](#tencrypt)
    - [Usage](#usage-1)
    - [Cipher algorithm](#cipher-algorithm)
  - [TFile](#tfile)
    - [Check if file exists](#check-if-file-exists)
    - [Create a file](#create-a-file)
    - [Checking if a file is writable](#checking-if-a-file-is-writable)
    - [Load file lines](#load-file-lines)
    - [Parsing .env files](#parsing-env-files)
    - [Parsing .json files](#parsing-json-files)
  - [TRandom](#trandom)
    - [Random strings](#random-strings)
    - [Random Numbers](#random-numbers)
    - [Random Numbers with leading zeros](#random-numbers-with-leading-zeros)
- [Traits](#traits)
  - [Empty Values Trait](#empty-values-trait)
  - [From Array Factory](#from-array-factory)
- [Contribute](#contribute)
- [License](#license)

# Requirements

- PHP 8+
- PHP mbstring extension installed and loaded.
- PHP openssl extension installed and loaded.
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

---

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

---

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

---

## TBase64 (url safe)

Encodes and decodes strings to Base64 that can be used on URLs.

### Usage <!-- omit in toc -->

```php
use Torugo\Util\TBase64\TBase64;

$b64 = TBase64::encode("My String"); // => "TXkgU3RyaW5n"
$decoded = TBase64::decode($b64); // => "My String"
```

---

## TEncrypt

Encrypts/Decrypts strings using symmetric keys.

> [!NOTE]
> **Symmetric key:**  
> Means that the key used for encryption must be the same for decryption.  
> Each cipher algorithm has a minimum key length in bytes, check the table bellow.

### Usage

```php
use Torugo\Util\TEncrypt\TEncrypt;
use Torugo\Util\TEncrypt\Enums\TCipher;

$text = "May the force be with you!";
$key = "ye-PaJYnFPluROpIFo146zhQNvKHbUkIKNMc2rkd8rE";

$encrypted = TEncrypt::encrypt($text, $key,);  // Encrypts the text
$decrypt = TEncrypt::decrypt($encrypted,$key); // Decrypts the encrypted text
```

### Cipher algorithm

Use the TCipher enum to set a new cipher algorithm.

**Setting the cipher algorithm**:
```php
TEncrypt::setCipher(TCipher::CAMELLIA_256_OFB);
```

List of all supported cipher algorithms, default is `AES_256_CFB`.

| Algorithm                                         | Min key length in bytes |
| ------------------------------------------------- | :---------------------: |
| AES_128_CBC                                       |           16            |
| AES_128_CFB                                       |           16            |
| AES_128_CFB1                                      |           16            |
| AES_128_CFB8                                      |           16            |
| AES_128_CTR                                       |           16            |
| AES_128_OFB                                       |           16            |
| AES_128_WRAP_PAD                                  |           16            |
| AES_192_CBC                                       |           24            |
| AES_192_CFB                                       |           24            |
| AES_192_CFB1                                      |           24            |
| AES_192_CFB8                                      |           24            |
| AES_192_CTR                                       |           24            |
| AES_192_OFB                                       |           24            |
| AES_192_WRAP_PAD                                  |           24            |
| AES_256_CBC                                       |           32            |
| <span style="color: green">**AES_256_CFB**</span> |           32            |
| AES_256_CFB1                                      |           32            |
| AES_256_CFB8                                      |           32            |
| AES_256_CTR                                       |           32            |
| AES_256_OFB                                       |           32            |
| AES_256_WRAP_PAD                                  |           32            |
| ARIA_128_CBC                                      |           16            |
| ARIA_128_CFB                                      |           16            |
| ARIA_128_CFB1                                     |           16            |
| ARIA_128_CFB8                                     |           16            |
| ARIA_128_CTR                                      |           16            |
| ARIA_128_OFB                                      |           16            |
| ARIA_192_CBC                                      |           24            |
| ARIA_192_CFB                                      |           24            |
| ARIA_192_CFB1                                     |           24            |
| ARIA_192_CFB8                                     |           24            |
| ARIA_192_CTR                                      |           24            |
| ARIA_192_OFB                                      |           24            |
| ARIA_256_CBC                                      |           32            |
| ARIA_256_CFB                                      |           32            |
| ARIA_256_CFB1                                     |           32            |
| ARIA_256_CFB8                                     |           32            |
| ARIA_256_CTR                                      |           32            |
| ARIA_256_OFB                                      |           32            |
| CAMELLIA_128_CBC                                  |           16            |
| CAMELLIA_128_CFB                                  |           16            |
| CAMELLIA_128_CFB1                                 |           16            |
| CAMELLIA_128_CFB8                                 |           16            |
| CAMELLIA_128_CTR                                  |           16            |
| CAMELLIA_128_OFB                                  |           16            |
| CAMELLIA_192_CBC                                  |           24            |
| CAMELLIA_192_CFB                                  |           24            |
| CAMELLIA_192_CFB1                                 |           24            |
| CAMELLIA_192_CFB8                                 |           24            |
| CAMELLIA_192_CTR                                  |           24            |
| CAMELLIA_192_OFB                                  |           24            |
| CAMELLIA_256_CBC                                  |           32            |
| CAMELLIA_256_CFB                                  |           32            |
| CAMELLIA_256_CFB1                                 |           32            |
| CAMELLIA_256_CFB8                                 |           32            |
| CAMELLIA_256_CTR                                  |           32            |
| CAMELLIA_256_OFB                                  |           32            |
| CHACHA20                                          |           32            |
| DES_EDE_CBC                                       |           16            |
| DES_EDE_CFB                                       |           16            |
| DES_EDE3_CFB                                      |           24            |
| DES_EDE3_CFB1                                     |           24            |
| DES_EDE3_CFB8                                     |           24            |
| DES_EDE3_OFB                                      |           24            |
| SM4_CBC                                           |           16            |
| SM4_CFB                                           |           16            |
| SM4_CTR                                           |           16            |
| SM4_OFB                                           |           16            |

---

## TFile

Manipulates text files parsing its content.

```php
use Torugo\Util\TFile\TFile;
```

### Check if file exists

The static method `exists` returns if a file exists in a given path,
you can use the argument `createIfNotExists` to create a file if it not exist.

```php
TFile::exists(string $path, bool $createIfNotExists): bool
```

### Create a file

The static method `create` tries to create a file on a given path.  
Returns `true` on success or `false` if not.

```php
TFile::create(string $path): bool
```

### Checking if a file is writable

```php
$file = new TFile(__DIR__ . "/file.txt");

$isWritable = $file->isWritable();
```

### Load file lines

Returns the lines of a text file as an array

```php
$file = new TFile(__DIR__ . "/file.txt");

$lines = $file->getLines();
```

### Parsing .env files

Parses the content of an `env` file as an associative array.

```php
$file = new TFile(__DIR__ . "/.env");

$env = $file->parseEnv();
```

### Parsing .json files
Loads a JSON file content and returns it as an associative array.  
In case of invalidation returns an empty array.

```php
$file = new TFile(__DIR__ . "/file.json");

$json = $file->parseJson();
```

---

## TRandom

Generates random strings and numbers

```php
use Torugo\Util\TRandom\TRandom;
```

### Random strings

Sets the source chars used to generate random strings.

```php
$rnd = TRandom::string(10); // Generates 10 chars long random string
```

#### Default source characters <!-- omit in toc -->

The default source characters are:  
`0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@!?#-_~^*`

#### Setting the source characters <!-- omit in toc -->
```php
TRandom::setCharacters("0123456789ABCDEF");
TRandom::string(6); // Generates something like "E4A010"
```

### Random Numbers

Generates a random integer between the given range.

```php
TRandom::number(1001, 9999); // Generates a random number between 1001 and 9999
```

### Random Numbers with leading zeros

Generates a positive random integer with leading zeros.

```php
TRandom::lzNumber(1, 9999, 4); // 0001 ... 9999
TRandom::lzNumber(1001, 999999, null); // 001001 ... 999999
```

---

# Traits

## Empty Values Trait

Returns an empty value for a specific type.

```php
use Torugo\Util\Traits\EmptyValues;

class MyClass {
    use EmptyValues;

    function myFunction() {
        // ...
        $type = $this->getEmptyValueForType(gettype($var));
        // ...
    }
}

```

## From Array Factory

Instantiates a class from a key=>value array.

1. The keys must be equal to the properties names.
2. All properties must be setted as public.

### Example <!-- omit in toc -->

```php
use Torugo\Util\Traits\FromArrayFactory;

class UserDto
{
    use FromArrayFactory;

    public string $name;
    public string $email;
    public string $password;
}

$payload = [
    "name" => "Full User Name",
    "email" => "user@gmail.com",
    "password" => "SuperStrongPassword!",
];

$instance = UserDto::fromArray($payload);
// $instance->name ==> "Full User Name"
// $instance->email ==> "user@gmail.com"
// $instance->password ==> "SuperStrongPassword!"

```

# Contribute

It is currently not open to contributions, I intend to make it available as soon as possible.

# License

This library is licensed under the MIT License - see the LICENSE file for details.
