<?php declare(strict_types=1);

namespace Torugo\Util\DateWriter;

require_once __DIR__ . "/Intl/Intl.php";

use DateTime;
use Torugo\Util\DateWriter\Intl\Intl;

class DateWriter
{
    /**
     * Transforms DateTime objects to written date/time.
     * @param \DateTime $dateTime PHP DateTime instance
     * @param string $language Language used to write the names of months and days of the week
     */
    public function __construct(private readonly DateTime $dateTime, private string $language = "en")
    {
        $this->loadLanguage($language);
    }


    private function loadLanguage(string $language): void
    {
        match (strtolower($language)) {
            "de" => $this->language = "de",
            "es" => $this->language = "es",
            "fr" => $this->language = "fr",
            "pt", "ptbr", "pt-br" => $this->language = "pt",
            default => $this->language = "en",
        };
    }


    /**
     * Summary of write
     * @param string $format
     * @return string
     */
    public function write(string $format): string
    {
        $lang = $this->language;
        $dt = $this->dateTime;

        $ordinals = [
            "st" => 0,
            "nd" => 1,
            "rd" => 2,
            "th" => 3,
        ];

        $ordinal = $ordinals[$dt->format("S")];

        $options = [
            "d" => $dt->format("d"),
            "j" => $dt->format("j"),
            "D" => Intl::SHORT_WEEK_DAY[$lang][$dt->format("w")],
            "l" => Intl::LONG_WEEK_DAY[$lang][$dt->format("w")],
            "N" => $dt->format("N"),
            "S" => Intl::ORDINAL_SUFFIX[$lang][$ordinal],
            "w" => $dt->format("w"),
            "z" => $dt->format("z"),
            "W" => $dt->format("W"),
            "m" => $dt->format("m"),
            "n" => $dt->format("n"),
            "F" => Intl::LONG_MONTH[$lang][$dt->format("n") - 1],
            "M" => Intl::SHORT_MONTH[$lang][$dt->format("n") - 1],
            "t" => $dt->format("t"),
            "L" => $dt->format("L"),
            "o" => $dt->format("o"),
            "X" => $dt->format("X"),
            "x" => $dt->format("x"),
            "Y" => $dt->format("Y"),
            "y" => $dt->format("y"),
            "a" => $dt->format("a"),
            "A" => $dt->format("A"),
            "B" => $dt->format("B"),
            "g" => $dt->format("g"),
            "G" => $dt->format("G"),
            "h" => $dt->format("h"),
            "H" => $dt->format("H"),
            "i" => $dt->format("i"),
            "s" => $dt->format("s"),
            "u" => $dt->format("u"),
            "v" => $dt->format("v"),
            "e" => $dt->format("e"),
            "I" => $dt->format("I"),
            "O" => $dt->format("O"),
            "P" => $dt->format("P"),
            "p" => $dt->format("p"),
            "T" => $dt->format("T"),
            "Z" => $dt->format("Z"),
            "c" => $dt->format("c"),
            "r" => $dt->format("r"),
            "U" => $dt->format("U"),
        ];

        $formatArray = str_split($format);

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
        preg_replace_callback("/\*{.*}/", function ($matches) {
            $v = $matches[0];
            $v = str_replace(["*{", "}"], "", $v);
            return mb_strtoupper($v);
        }, $value);

        return $value;
    }


    private function resolveLowercaseMarks(string $value): string
    {
        preg_replace_callback("/%{.*}/", function ($matches) {
            $v = $matches[0];
            $v = str_replace(["%{", "}"], "", $v);
            return  mb_strtolower($v);
        }, $value);

        return $value;
    }
}
