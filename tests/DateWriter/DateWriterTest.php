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
        $result = $dw->write("[São Paulo,] d [de] o [de] Y");
        $this->assertEquals("São Paulo, 01 de Agosto de 2017", $result);
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
            $result = $dw->write("o");
            $this->assertEquals($month, $result);
        }
    }

    #[TestDox("Should test all available options")]
    public function testAllAvailableOptions()
    {
        $dateTime = \DateTime::createFromFormat("Y-m-d H:i:s.u", "2017-08-01 15:30:45.8765");
        $dw = new DateWriter($dateTime, "pt");

        $options = [
            "d" => "01",
            "D" => "1",
            "m" => "08",
            "M" => "8",
            "o" => "Agosto",
            "n" => "Ago",
            "w" => "Terça-feira",
            "e" => "Ter",
            "y" => "17",
            "Y" => "2017",
            "h" => "15",
            "H" => "15",
            "i" => "30",
            "s" => "45",
            "u" => "876500",
            "a" => "pm",
            "A" => "PM",
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
            "U{o}" => "AGOSTO",
            "U{n}" => "AGO",
            "U{w}" => "TERÇA-FEIRA",
            "U{e}" => "TER",
            "U{o} U{n} U{e}" => "AGOSTO AGO TER",
            "[U{test}]" => "TEST",
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
            "L{o}" => "march",
            "L{n}" => "mar",
            "L{w}" => "sunday",
            "L{e}" => "sun",
            "L{o} L{n} L{e}" => "march mar sun",
            "[L{TEST}]" => "test",
        ];

        foreach ($options as $option => $expected) {
            $result = $dw->write($option);
            $this->assertEquals($expected, $result);
        }
    }
}
