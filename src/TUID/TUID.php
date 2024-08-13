<?php declare(strict_types=1);

namespace Torugo\Util\TUID;

use DateTime;
use Torugo\Util\CDT\CDT;

/**
 * TUID (Torugo Unique Identifier) generates a random unique ID with date and time.
 */
class TUID
{
    private const PATTERN_SHORT = "/^[A-Z][A-Z0-9]{6}-TS[A-Z0-9]{10}$/";
    private const PATTERN_MEDIUM = "/^[A-Z][A-Z0-9]{7}-[A-Z0-9]{4}-TM[A-Z0-9]{10}$/";
    private const PATTERN_LONG = "/^[A-Z][A-Z0-9]{7}-[A-Z0-9]{4}-[A-Z0-9]{9}-TL[A-Z0-9]{10}$/";

    /**
     * Generates 20 char long ID
     * @return string
     */
    public static function short(): string
    {
        $a = self::random(7, true);
        $b = self::getCDT();

        return "$a-TS$b";
    }


    /**
     * Generates 26 char long ID
     * @return string
     */
    public static function medium(): string
    {
        $a = self::random(8, true);
        $b = self::random(4);
        $c = self::getCDT();

        return strtoupper("$a-$b-TM$c");
    }


    /**
     * Generates 36 char long ID
     * @return string
     */
    public static function long(): string
    {
        $a = self::random(8, true);
        $b = self::random(4);
        $c = self::random(9);
        $e = self::getCDT();

        return strtoupper("$a-$b-$c-TL$e");
    }


    /**
     * Generates a random string with a given length.
     * @param int $length Length of generated random string
     * @param bool $initWithAlpha Start string with alphabetic chars only
     * @return string
     */
    private static function random(int $length, bool $initWithAlpha = false): string
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $rnd = '';
        for ($i = 0; $i < $length; $i++) {
            if ($i == 0 && $initWithAlpha) {
                $rnd .= $chars[mt_rand(0, 25)];
                continue;
            }

            $rnd .= $chars[mt_rand(0, 35)];
        }

        return $rnd;
    }


    /**
     * Generates a 10 chars long CDT with leading zeros
     * @return string
     */
    private static function getCDT(): string
    {
        $cdt = CDT::get();
        return str_pad($cdt, 10, "0", STR_PAD_LEFT);
    }


    /**
     * Validates a TUID
     * @param string $tid TUID to be validated
     * @return bool
     */
    public static function validate(string $tid): bool
    {
        $len = strlen($tid);

        $pattern = match ($len) {
            20 => self::PATTERN_SHORT,
            26 => self::PATTERN_MEDIUM,
            36 => self::PATTERN_LONG,
            default => false,
        };

        if ($pattern === false) {
            return false;
        }

        $match = preg_match_all($pattern, $tid);
        return $match == 1;
    }


    public static function getDateTime(string $tid): DateTime|false
    {
        if (self::validate($tid) == false) {
            return false;
        }

        $parts = explode("-", $tid);
        $cdt = end($parts);
        $cdt = substr($cdt, -10);
        $cdt = ltrim($cdt, "0");

        $cdt = CDT::toDateTime($cdt);

        return $cdt;
    }
}
