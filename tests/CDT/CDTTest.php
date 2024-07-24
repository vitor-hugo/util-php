<?php declare(strict_types=1);

namespace Tests\CDT;

use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Torugo\Util\CDT\CDT;

#[Group("CDT")]
#[TestDox("CDT")]
class CDTTest extends TestCase
{
    #[TestDox("Should generate a CDT from current DateTime")]
    public function testShouldGenerateFromCurrentDateTime()
    {
        $cdt = CDT::get();
        $this->assertTrue(strlen($cdt) >= 9);
        $this->assertMatchesRegularExpression("/^[0-9A-Z]{9,}$/", $cdt);
    }


    #[TestDox("Should create from PHP DateTime object")]
    public function testShouldCreateFromDateTime()
    {
        $cdt = CDT::fromDateTime(DateTime::createFromFormat("Y-m-d H:i:s.u", "2017-08-01 14:45:56.789"));
        $this->assertEquals("OU0H0K0LX", $cdt);
    }


    #[TestDox("Should create from timestamp")]
    public function testShouldCreateFromTimestamp()
    {
        $cdt = CDT::fromTimestamp(416410245.1234);
        $this->assertEquals("6VX4790YA", $cdt);

        $cdt = CDT::fromTimestamp(1721410862);
        $this->assertEquals("SGVT4E000", $cdt);
    }


    #[TestDox("Should throw InvalidArgumentException on invalid timestamp")]
    public function testShouldThrowWhenTimestampIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("CDT: Invalid timestamp value.");
        CDT::fromTimestamp(-10);
    }



    #[TestDox("Should validate correctly")]
    public function testShouldValidateCorrectly()
    {
        $this->assertTrue(CDT::validateCDT("6VX4790YA"));
        $this->assertFalse(CDT::validateCDT("6VX47-90YA"));
        $this->assertFalse(CDT::validateCDT("VX4"));
    }


    #[TestDox("Should convert to PHP DateTime object")]
    public function testShouldConvertToDateTimeObject()
    {
        $dateTime = CDT::toDateTime("6VX4790YA");
        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertEquals("1983", $dateTime->format("Y"));
        $this->assertEquals("03", $dateTime->format("m"));
        $this->assertEquals("13", $dateTime->format("d"));
        $this->assertEquals("13", $dateTime->format("H"));
        $this->assertEquals("30", $dateTime->format("i"));
        $this->assertEquals("45", $dateTime->format("s"));
        $this->assertEquals("123400", $dateTime->format("u"));
        $this->assertFalse(CDT::toDateTime("0YA"));
    }
}
