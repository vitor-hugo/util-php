<?php declare(strict_types=1);

namespace Torugo\Util\TRandom;

use InvalidArgumentException;

/**
 * Generates random strings and numbers
 */
class TRandom
{
    /**
     * Source chars for random string generator
     * @var string
     */
    private static string $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@!?#-_~^*";


    public static function getChars(): string
    {
        return self::$chars;
    }

    /**
     * Sets the source chars used to generate random strings.
     * Default is "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@!?#-_~^*".
     * @param string $chars At least two diferent characters.
     * @throws \InvalidArgumentException When $chars arg length is lesser than two.
     * @return void
     */
    public static function setCharacters(string $chars): void
    {
        $chars = count_chars($chars, 3);

        if (strlen($chars) < 2) {
            throw new InvalidArgumentException("(TRandom::setCharacters) Insufficient number of characters, you must provide at least two.");
        }

        self::$chars = $chars;
    }


    /**
     * Generates a randon string with a given length.
     * @param int $length Should be greater than zero.
     * @throws \InvalidArgumentException When length is lesser than one
     * @return string
     */
    public static function string(int $length): string
    {
        if ($length < 1) {
            throw new InvalidArgumentException("(TRandom::string) The length argument must be greater than zero.");
        }

        $rnd = '';
        $charsLen = strlen(self::$chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $rnd .= self::$chars[rand(0, $charsLen)];
        }

        return $rnd;
    }


    /**
     * Generates a random integer between the given range.
     * @param int $min The lowest value to be returned.
     * @param int $max The greates value to be returned.
     * @return int
     */
    public static function number(int $min, int $max): int
    {
        if ($min > $max) {
            list($min, $max) = [$max, $min];
        }

        return random_int($min, $max);
    }


    /**
     * Generates a positive random integer with leading zeros.
     * @param int $min The lowest positive number to be returned.
     * @param int $max The greates positive number to be returned.
     * @param int|null $length Should be greather than or equal to $max value length.
     * @throws \InvalidArgumentException When min or max numbers is negative
     * @return string
     */
    public static function lzNumber(
        int $min,
        int $max,
        ?int $length = null
    ): string {
        if ($min < 0 || $max < 0) {
            throw new InvalidArgumentException("(TRandom::lzNumber) The min and max arguments must be positive integers.");
        }

        $maxLen = strlen((string) $max);
        $length = $length ?? $maxLen;

        if ($length < $maxLen) {
            $length = $maxLen;
        }

        $rnd = self::number($min, $max);

        return str_pad((string) $rnd, $length, "0", STR_PAD_LEFT);
    }
}
