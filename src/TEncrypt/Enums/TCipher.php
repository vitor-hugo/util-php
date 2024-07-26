<?php declare(strict_types=1);

namespace Torugo\Util\TEncrypt\Enums;

use InvalidArgumentException;

enum TCipher: string
{
    case AES_128_CBC = "aes-128-cbc";
    case AES_128_CFB = "aes-128-cfb";
    case AES_128_CFB1 = "aes-128-cfb1";
    case AES_128_CFB8 = "aes-128-cfb8";
    case AES_128_CTR = "aes-128-ctr";
    case AES_128_OFB = "aes-128-ofb";
    case AES_128_WRAP_PAD = "aes-128-wrap-pad";
    case AES_192_CBC = "aes-192-cbc";
    case AES_192_CFB = "aes-192-cfb";
    case AES_192_CFB1 = "aes-192-cfb1";
    case AES_192_CFB8 = "aes-192-cfb8";
    case AES_192_CTR = "aes-192-ctr";
    case AES_192_OFB = "aes-192-ofb";
    case AES_192_WRAP_PAD = "aes-192-wrap-pad";
    case AES_256_CBC = "aes-256-cbc";
    case AES_256_CFB = "aes-256-cfb";
    case AES_256_CFB1 = "aes-256-cfb1";
    case AES_256_CFB8 = "aes-256-cfb8";
    case AES_256_CTR = "aes-256-ctr";
    case AES_256_OFB = "aes-256-ofb";
    case AES_256_WRAP_PAD = "aes-256-wrap-pad";
    case ARIA_128_CBC = "aria-128-cbc";
    case ARIA_128_CFB = "aria-128-cfb";
    case ARIA_128_CFB1 = "aria-128-cfb1";
    case ARIA_128_CFB8 = "aria-128-cfb8";
    case ARIA_128_CTR = "aria-128-ctr";
    case ARIA_128_OFB = "aria-128-ofb";
    case ARIA_192_CBC = "aria-192-cbc";
    case ARIA_192_CFB = "aria-192-cfb";
    case ARIA_192_CFB1 = "aria-192-cfb1";
    case ARIA_192_CFB8 = "aria-192-cfb8";
    case ARIA_192_CTR = "aria-192-ctr";
    case ARIA_192_OFB = "aria-192-ofb";
    case ARIA_256_CBC = "aria-256-cbc";
    case ARIA_256_CFB = "aria-256-cfb";
    case ARIA_256_CFB1 = "aria-256-cfb1";
    case ARIA_256_CFB8 = "aria-256-cfb8";
    case ARIA_256_CTR = "aria-256-ctr";
    case ARIA_256_OFB = "aria-256-ofb";
    case CAMELLIA_128_CBC = "camellia-128-cbc";
    case CAMELLIA_128_CFB = "camellia-128-cfb";
    case CAMELLIA_128_CFB1 = "camellia-128-cfb1";
    case CAMELLIA_128_CFB8 = "camellia-128-cfb8";
    case CAMELLIA_128_CTR = "camellia-128-ctr";
    case CAMELLIA_128_OFB = "camellia-128-ofb";
    case CAMELLIA_192_CBC = "camellia-192-cbc";
    case CAMELLIA_192_CFB = "camellia-192-cfb";
    case CAMELLIA_192_CFB1 = "camellia-192-cfb1";
    case CAMELLIA_192_CFB8 = "camellia-192-cfb8";
    case CAMELLIA_192_CTR = "camellia-192-ctr";
    case CAMELLIA_192_OFB = "camellia-192-ofb";
    case CAMELLIA_256_CBC = "camellia-256-cbc";
    case CAMELLIA_256_CFB = "camellia-256-cfb";
    case CAMELLIA_256_CFB1 = "camellia-256-cfb1";
    case CAMELLIA_256_CFB8 = "camellia-256-cfb8";
    case CAMELLIA_256_CTR = "camellia-256-ctr";
    case CAMELLIA_256_OFB = "camellia-256-ofb";
    case CHACHA20 = "chacha20";
    case DES_EDE_CBC = "des-ede-cbc";
    case DES_EDE_CFB = "des-ede-cfb";
    case DES_EDE3_CFB = "des-ede3-cfb";
    case DES_EDE3_CFB1 = "des-ede3-cfb1";
    case DES_EDE3_CFB8 = "des-ede3-cfb8";
    case DES_EDE3_OFB = "des-ede3-ofb";
    case SM4_CBC = "sm4-cbc";
    case SM4_CFB = "sm4-cfb";
    case SM4_CTR = "sm4-ctr";
    case SM4_OFB = "sm4-ofb";

    public static function fromString(string $cipherMethod): self
    {
        $cipherMethod = strtolower($cipherMethod);
        foreach (self::cases() as $cipher) {
            if ($cipherMethod === $cipher->value) {
                return $cipher;
            }
        }

        throw new InvalidArgumentException("'$cipherMethod' is a invalid cipher method.");
    }
}
