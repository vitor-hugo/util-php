<?php declare(strict_types=1);

namespace Tests\TRandom;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Torugo\Util\TRandom\TRandom;

#[Group("TRandom")]
#[TestDox("TRandom")]
class TRandomTest extends TestCase
{
    #[TestDox("number: Should genarate a random integer between a given range")]
    public function testShouldGenerateARandomNumber()
    {
        $ranges = [
            [0, 100],
            [-100, 100],
            [-200, -100],
            [PHP_INT_MIN, PHP_INT_MAX]
        ];

        foreach ($ranges as $range) {
            $min = $range[0];
            $max = $range[1];
            $rnd = TRandom::number($min, $max);
            $this->assertIsInt($rnd);
            $this->assertTrue($rnd >= $min && $rnd <= $max);
        }
    }


    #[TestDox("number: Should swap min and max args when min is greater than max")]
    public function testShouldSwapMinAndMaxArgs()
    {
        $min = 1000;
        $max = 500;
        $rnd = TRandom::number($min, $max);
        $this->assertIsInt($rnd);
        $this->assertTrue($rnd >= $max && $rnd <= $min);
    }


    #[TestDox("lzNumber: Should generates a positive random integer with leading zeros.")]
    public function testShouldGenerateARandomNumberWithLeadingZeros()
    {
        $args = [
            [0, 100, 4],
            [1000, 9999, 6],
            [0, 999999, 10],
            [0, PHP_INT_MAX, 20]
        ];

        foreach ($args as $arg) {
            $min = $arg[0];
            $max = $arg[1];
            $len = $arg[2];
            $rnd = TRandom::lzNumber($min, $max, $len);
            $this->assertEquals($len, strlen($rnd));
        }
    }


    #[TestDox("lzNumber: Should fix length arg when null or lesser than max value length.")]
    public function testShouldFixLength()
    {
        $rnd = TRandom::lzNumber(0, 9999, null);
        $this->assertEquals(4, strlen($rnd));

        $rnd = TRandom::lzNumber(0, 9999, 3);
        $this->assertEquals(4, strlen($rnd));
    }


    #[TestDox("lzNumber: Should throw InvalidArgumentException when min or max args are negative")]
    public function testShouldThrowWhenMinOrMaxArgsAreInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("(TRandom::lzNumber) The min and max arguments must be positive integers.");
        TRandom::lzNumber(-200, -100, 3);
    }


    #[TestDox("string: Should generate a random string with default chars")]
    public function testShouldGenerateWithDefaultChars()
    {
        $lenghts = [5, 10, 20, 30, 47, 54];
        foreach ($lenghts as $len) {
            $rnd = TRandom::string($len);
            $this->assertEquals($len, strlen($rnd));
        }
    }


    #[TestDox("setCharacters: Should throw InvalidArgumentException when setting insufficient chars")]
    public function testShouldThrowOnInsufficientChars()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("(TRandom::setCharacters) Insufficient number of characters, you must provide at least two.");
        TRandom::setCharacters("A");
    }


    #[TestDox("setCharacters: Should remove duplicated characters")]
    public function testShouldRemoveDuplicatedChars()
    {
        TRandom::setCharacters("1122334455");
        $this->assertEquals("12345", TRandom::getChars());
    }


    #[TestDox("string: Should generate a random string with custom chars")]
    public function testShouldGenerateWithCustomChars()
    {
        TRandom::setCharacters("0123456789");
        $rnd = TRandom::string(20);
        $this->assertMatchesRegularExpression("/^[0-9]{20,20}$/", $rnd);

        TRandom::setCharacters("0123456789abcdefghijklmnopqrstuvwxyz");
        $rnd = TRandom::string(20);
        $this->assertMatchesRegularExpression("/^[0-9a-z]{20,20}$/", $rnd);

        TRandom::setCharacters("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
        $rnd = TRandom::string(20);
        $this->assertMatchesRegularExpression("/^[A-Z]{20,20}$/", $rnd);

        TRandom::setCharacters("01");
        $rnd = TRandom::string(10);
        $this->assertMatchesRegularExpression("/^[01]{10,10}$/", $rnd);
    }

    #[TestDox("Should throw InvalidArgumentException when length is lesser than one")]
    public function testShouldThrowWhenLengthIsLesserThanOne()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("(TRandom::string) The length argument must be greater than zero.");
        TRandom::string(0);
    }
}
