<?php declare(strict_types=1);

namespace Torugo\Util\CDT;

use DateTime;
use DateTimeZone;

/**
 * CDT (Compressed Date and Time) is a way of storing date and time including milliseconds.
 */
class CDT
{
    /**
     * Generates a CDT from current date/time.
     * @return string
     */
    public static function get(): string
    {
        $currentTimezone = date_default_timezone_get();
        date_default_timezone_set('UTC');

        $timestamp = microtime(true);
        $cdt = self::assembleCDT($timestamp);

        date_default_timezone_set($currentTimezone);

        return $cdt;
    }


    /**
     * Builds the final CDT string from a timestamp or microtime
     * @param int|float $timestamp timestamp or microtime
     * @return string
     */
    private static function assembleCDT(int|float $timestamp): string
    {
        $parts = explode(".", (string) $timestamp);

        $sec = $parts[0];
        $sec = strtoupper(base_convert((string) $sec, 10, 36));

        $milli = $parts[1] ?? "0";
        $milli = strtoupper(base_convert((string) $milli, 10, 36));
        $milli = str_pad($milli, 3, "0", STR_PAD_LEFT);

        return "{$sec}{$milli}";
    }


    /**
     * Generates a CDT from a timestamp or microtime
     * @param int|float $timestamp
     * @throws \InvalidArgumentException
     * @return string
     */
    public static function fromTimestamp(int|float $timestamp): string
    {
        if ($timestamp < 0 || $timestamp > 99999999999) {
            throw new \InvalidArgumentException("CDT: Invalid timestamp value.");
        }

        return self::assembleCDT($timestamp);
    }


    /**
     * Generates a CDT from a PHP DateTime object
     * @param \DateTime $dateTime
     * @return string
     */
    public static function fromDateTime(DateTime $dateTime): string
    {
        $timestamp = $dateTime->format("U.u");
        return self::fromTimestamp((double) $timestamp);
    }


    /**
     * Validates a CDT string
     * @param string $cdt
     * @return bool
     */
    public static function validateCDT(string $cdt): bool
    {
        if (preg_match_all("/^[a-zA-Z0-9]{4,10}$/", $cdt)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Converts a CDT to a PHP DateTime object
     * @param string $cdt
     * @return \DateTime|false
     */
    public static function toDateTime(string $cdt): DateTime|false
    {
        if (self::validateCDT($cdt) === false) {
            return false;
        }

        $timestamp = self::toMicrotime($cdt);
        $timeZone = new DateTimeZone(date_default_timezone_get());
        $date = DateTime::createFromFormat('U.u', (string) $timestamp, $timeZone);

        return $date;
    }


    /**
     * Converts a CDT to a float microtime number
     * @param string $cdt
     * @return float
     */
    public static function toMicrotime(string $cdt): float
    {
        $pos = strlen($cdt) - 3;
        $str = substr($cdt, 0, $pos) . "." . substr($cdt, $pos);
        $parts = explode(".", $str);
        $sec = base_convert($parts[0], 36, 10);
        $milli = str_pad(base_convert($parts[1], 36, 10), 4, "0", STR_PAD_LEFT);

        return (double) "{$sec}.{$milli}";
    }
}
