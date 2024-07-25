<?php declare(strict_types=1);

namespace Torugo\Util\TBase64;

/**
 * Encodes and decodes strings to base64 that can be used on URLs.
 * This is a static class, doesn't have to instantiate
 */
class TBase64
{
    /**
     * Encodes a string to Base64 URL safe
     * @param mixed $str
     * @return string
     */
    public static function encode(string $str): string
    {
        if (empty($str)) {
            return '';
        }

        $base64 = base64_encode($str);
        $result = strtr($base64, "+/", "-_");
        return rtrim($result, "=");
    }


    /**
     * Decodes a base64 URL safe string
     * @param mixed $encoded
     * @return string
     */
    public static function decode(?string $encoded): string
    {
        if (empty($encoded)) {
            return '';
        }

        $len = strlen($encoded) % 4;

        if ($len) {
            $pad = 4 - $len;
            $encoded .= str_repeat("=", $pad);
        }

        $result = strtr($encoded, "-_", "+/");
        return base64_decode($result);
    }
}
