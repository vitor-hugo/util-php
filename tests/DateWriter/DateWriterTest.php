<?php declare(strict_types=1);

namespace Tests\DateWriter;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Torugo\Util\DateWriter\DateWriter;

#[Group("DateWriter")]
#[TestDox("DateWriter")]
class DateWriterTest extends TestCase
{
    #[TestDox("Should write date skipping everything between square brackets")]
    public function testShouldWriteDateSkippingEverythingBetweenSquareBrackets()
    {
        $dateTime = \DateTime::createFromFormat("Y-m-d H:i:s", "2017-08-01 15:30:45");
        $dw = new DateWriter($dateTime, "pt");
        $result = $dw->write("[São Paulo,] j [de] %{F} [de] Y");
        $this->assertEquals("São Paulo, 1 de agosto de 2017", $result);
    }

    #[TestDox("Should load languages correctly")]
    public function testShouldBeValid()
    {
        $dateTime = \DateTime::createFromFormat("Y-m-d H:i:s", "2017-08-01 15:30:45");
        $languages = [
            "de" => "August",
            "en" => "August",
            "es" => "Agosto",
            "fr" => "Août",
            "pt" => "Agosto",
            "xy" => "August",
        ];

        foreach ($languages as $lang => $month) {
            $dw = new DateWriter($dateTime, $lang);
            $result = $dw->write("F");
            $this->assertEquals($month, $result);
        }
    }

    #[TestDox("Should test all available options")]
    public function testAllAvailableOptions()
    {
        $dateTime = \DateTime::createFromFormat("Y-m-d H:i:s.u", "2017-08-01 15:30:45.8765", new \DateTimeZone("America/Sao_Paulo"));
        $dw = new DateWriter($dateTime, "pt");

        $options = [
            "d" => "01",
            "j" => "1",
            "D" => "Ter",
            "l" => "Terça-feira",
            "N" => "2",
            "S" => "º",
            "w" => "2",
            "z" => "212",
            "W" => "31",
            "m" => "08",
            "n" => "8",
            "F" => "Agosto",
            "M" => "Ago",
            "t" => "31",
            "L" => "0",
            "o" => "2017",
            "X" => "+2017",
            "x" => "2017",
            "Y" => "2017",
            "y" => "17",
            "a" => "pm",
            "A" => "PM",
            "B" => "813",
            "g" => "3",
            "G" => "15",
            "h" => "03",
            "H" => "15",
            "i" => "30",
            "s" => "45",
            "u" => "876500",
            "v" => "876",
            "e" => "America/Sao_Paulo",
            "I" => "0",
            "O" => "-0300",
            "P" => "-03:00",
            "p" => "-03:00",
            "T" => "-03",
            "Z" => "-10800",
            "c" => "2017-08-01T15:30:45-03:00",
            "r" => "Tue, 01 Aug 2017 15:30:45 -0300",
            "U" => "1501612245",
        ];

        foreach ($options as $option => $expected) {
            $result = $dw->write($option);
            $this->assertEquals($expected, $result);
        }
    }

    #[TestDox("Should convert marked parts to uppercase")]
    public function testShouldConvertMarkedPartsToUppercase()
    {
        $dateTime = \DateTime::createFromFormat("Y-m-d H:i:s", "2017-08-01 15:30:45");
        $dw = new DateWriter($dateTime, "pt");

        $options = [
            "*{F}" => "AGOSTO",
            "*{M}" => "AGO",
            "*{l}" => "TERÇA-FEIRA",
            "*{D}" => "TER",
            "*{F} *{M} *{D}" => "AGOSTO AGO TER",
            "[*{test}]" => "TEST",
        ];

        foreach ($options as $option => $expected) {
            $result = $dw->write($option);
            $this->assertEquals($expected, $result);
        }
    }

    #[TestDox("Should convert marked parts to lowercase")]
    public function testShouldConvertMarkedPartsToLowercase()
    {
        $dateTime = \DateTime::createFromFormat("Y-m-d H:i:s", "1983-03-13 13:30:45");
        $dw = new DateWriter($dateTime, "en");

        $options = [
            "%{F}" => "march",
            "%{M}" => "mar",
            "%{l}" => "sunday",
            "%{D}" => "sun",
            "%{F} %{M} %{D}" => "march mar sun",
            "[%{TEST}]" => "test",
        ];

        foreach ($options as $option => $expected) {
            $result = $dw->write($option);
            $this->assertEquals($expected, $result);
        }
    }
}
