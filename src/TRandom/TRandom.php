<?php declare(strict_types=1);

namespace Torugo\Util\TRandom;

use Exception;
use InvalidArgumentException;

/**
 * Generates random strings and numbers
 */
class TRandom
{
    /**
     * Alphabetical characters
     * @var string
     */
    public string $alpha = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

    /**
     * Numeric characters
     * @var string
     */
    public string $numbers = "0123456789";

    /**
     * Special characters
     * @var string
     */
    public string $symbols = "!;#%&()*+,-./:;<=>?@[]^_{|}~";

    /**
     * Should include alphabetical characters on randomic strings
     * @var bool
     */
    public bool $includeAlpha = true;

    /**
     * Should include numeric characters on randomic strings
     * @var bool
     */
    public bool $includeNumbers = true;

    /**
     * Should include special characters on randomic strings
     * @var bool
     */
    public bool $includeSymbols = true;

    /**
     * Randomic string should start with an alphabetical character
     * @var bool
     */
    public bool $startWithAlphaChar = false;

    /**
     * Generates a random string with a given length.
     * @param int $length Should be greater than zero.
     * @return string
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function string(int $length): string
    {
        if ($length < 1) {
            throw new InvalidArgumentException("The length argument must be greater than zero.");
        }

        $chars = "";

        if ($this->includeAlpha) {
            $chars .= $this->alpha;
        }

        if ($this->includeNumbers) {
            $chars .= $this->numbers;
        }

        if ($this->includeSymbols) {
            $chars .= $this->symbols;
        }

        if (empty($chars)) {
            throw new Exception("Could not generate a randomic string, please check class parameters.");
        }

        $rnd = "";
        $charsLen = strlen($chars) - 1;

        $startWithAlpha = $this->startWithAlphaChar;

        for ($i = 0; $i < $length; $i++) {
            if ($startWithAlpha) {
                $rnd .= $this->alpha[mt_rand(0, strlen($this->alpha) - 1)];
                $startWithAlpha = false;
                continue;
            }

            $rnd .= $chars[rand(0, $charsLen)];
        }

        return $rnd;
    }


    /**
     * Generates a random integer between the given range.
     * @param int $min The lowest value to be returned.
     * @param int $max The greates value to be returned.
     * @return int
     */
    public function number(int $min, int $max): int
    {
        if ($min > $max) {
            [$min, $max] = [$max, $min];
        }

        return mt_rand($min, $max);
    }


    /**
     * Generates a positive random integer with leading zeros.
     * @param int $min The lowest positive number to be returned.
     * @param int $max The greates positive number to be returned.
     * @param int|null $length Should be greather than or equal to $max value length.
     * @throws \InvalidArgumentException When min or max numbers is negative
     * @return string
     */
    public function lzNumber(
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
