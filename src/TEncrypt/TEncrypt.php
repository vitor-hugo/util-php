<?php declare(strict_types=1);

namespace Torugo\Util\TEncrypt;

use Exception;
use InvalidArgumentException;
use Torugo\Util\TBase64\TBase64;
use Torugo\Util\TEncrypt\Enums\TCipher;

/**
 * Encrypts/Decrypts data using symmetric keys.
 */
class TEncrypt
{
    private static TCipher $cipher = TCipher::AES_256_CFB;

    /**
     * Returns the current cipher algorithm
     * @return \Torugo\Util\TEncrypt\Enums\TCipher
     */
    public static function getCipher(): TCipher
    {
        return self::$cipher;
    }


    /**
     * Sets the cipher algorithm
     * @param \Torugo\Util\TEncrypt\Enums\TCipher $cipher
     * @return void
     */
    public static function setCipher(TCipher $cipher): void
    {
        self::$cipher = $cipher;
    }


    /**
     * Encrypts a value using the current symmetric key and cipher method
     * @param string $value Value to be encrypted
     * @throws \Exception When encryption fails
     * @return string
     */
    public static function encrypt(string $value, string $key): string
    {
        if (empty(trim($value))) {
            return "";
        }

        self::validateKey($key);

        $ivLength = @openssl_cipher_iv_length(self::$cipher->value);
        $iv = @openssl_random_pseudo_bytes($ivLength);

        $encrypted = @openssl_encrypt(
            $value,
            self::$cipher->value,
            $key,
            OPENSSL_DONT_ZERO_PAD_KEY,
            $iv
        );

        $encrypted = TBase64::encode($encrypted);
        $iv = TBase64::encode($iv);

        return "$encrypted.$iv";
    }


    /**
     * Validates the symmetric key
     * @param string $key Key to be validated
     * @return void
     * @throws InvalidArgumentException When the key is invalid
     */
    private static function validateKey(string $key): void
    {
        $bytes = @openssl_cipher_key_length(self::$cipher->value);
        if (strlen($key) < $bytes) {
            throw new InvalidArgumentException("TEncrypt: Invalid key for cipher '"
                . self::$cipher->value
                . "', it should be at least $bytes bytes long.");
        }
    }


    /**
     * Decrypts an encrypted value using the current key and cipher method
     * @param string $encrypted Encrypted data
     * @throws \Exception
     * @return string
     */
    public static function decrypt(string $encrypted, string $key): string
    {
        if (empty(trim($encrypted))) {
            return "";
        }

        self::validateKey($key);

        $parts = explode('.', $encrypted);

        if (count($parts) != 2) {
            throw new Exception('TEncrypt: Invalid encrypted string.');
        }

        $value = TBase64::decode($parts[0]);
        $iv = TBase64::decode($parts[1]);

        $result = openssl_decrypt($value, self::$cipher->value, $key, 0, $iv);

        if ($result == false) {
            throw new Exception('TEncrypt: Could not decrypt the data.');
        }

        return $result;
    }


    /**
     * Generates a best fit symmetric key for a given cipher
     * @param \Torugo\Util\TEncrypt\Enums\TCipher $cipher Cipher method
     * @return string
     */
    public static function generateKeyForCipher(TCipher $cipher): string
    {
        $bytes = @openssl_cipher_key_length($cipher->value);
        return self::generateKey($bytes);
    }


    /**
     * Generates a key with a given number of bytes
     * @param int $bytes Key length in bytes
     * @return string
     */
    public static function generateKey(int $bytes = 32): string
    {
        $key = '';
        $strong = false;

        while ($strong == false) {
            try {
                $key = @openssl_random_pseudo_bytes($bytes, $strong);
                $key = TBase64::encode($key);
            } catch (\Throwable $th) {
                throw new Exception("TEncrypt: Invalid byte size");
            }
        }

        return $key;
    }
}
