<?php declare(strict_types=1);

namespace Torugo\Util\DateWriter;

require_once __DIR__ . "/Intl/Intl.php";

use DateTime;
use Intl;

class DateWriter
{
    public function __construct(private DateTime $dateTime, private string $language = "en")
    {
        $this->loadLanguage($language);
    }


    private function loadLanguage(string $language)
    {
        match (strtolower($language)) {
            "de" => $this->language = "de",
            "en", "en-us" => $this->language = "en",
            "es" => $this->language = "es",
            "fr" => $this->language = "fr",
            "pt", "ptbr", "pt-br" => $this->language = "pt",
            default => $this->language = "en",
        };
    }


    public function write(string $format): string
    {
        $lang = $this->language;
        $dt = $this->dateTime;

        $options = [
            "d" => $dt->format("d"),
            "D" => (int) $dt->format("d"),
            "m" => $dt->format("m"),
            "M" => $dt->format("n"),
            "o" => Intl::LONG_MONTH[$lang][$dt->format("n") - 1],
            "n" => Intl::SHORT_MONTH[$lang][$dt->format("n") - 1],
            "w" => Intl::LONG_WEEK_DAY[$lang][$dt->format("w")],
            "e" => Intl::SHORT_WEEK_DAY[$lang][$dt->format("w")],
            "y" => $dt->format("y"),
            "Y" => $dt->format("Y"),
            "h" => $dt->format("G"),
            "H" => $dt->format("H"),
            "i" => $dt->format("i"),
            "s" => $dt->format("s"),
            "u" => $dt->format("u"),
            "a" => $dt->format("a"),
            "A" => $dt->format("A"),
        ];

        $formatArray = str_split($format, 1);

        $result = "";
        $ignore = false;

        foreach ($formatArray as $f) {
            $ignore = $f === "[" ? true : $ignore;
            $ignore = $f === "]" ? false : $ignore;

            if ($f === "[" || $f === "]") {
                continue;
            }

            if ($ignore) {
                $result .= $f;
                continue;
            }

            $result .= $options[$f] ?? $f;
        }

        $result = $this->resolveUppercaseMarks($result);
        $result = $this->resolveLowercaseMarks($result);

        return $result;
    }


    private function resolveUppercaseMarks(string $value): string
    {
        $value = preg_replace_callback("/U{.*}/", function ($matches) {
            $v = $matches[0];
            $v = str_replace(["U{", "}"], "", $v);
            $v = mb_strtoupper($v);
            return $v;
        }, $value);

        return $value;
    }


    private function resolveLowercaseMarks(string $value): string
    {
        $value = preg_replace_callback("/L{.*}/", function ($matches) {
            $v = $matches[0];
            $v = str_replace(["L{", "}"], "", $v);
            $v = mb_strtolower($v);
            return $v;
        }, $value);

        return $value;
    }
}
