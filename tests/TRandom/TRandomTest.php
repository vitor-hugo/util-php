<?php declare(strict_types=1);

namespace Tests\TRandom;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Torugo\Util\TRandom\TRandom;

#[Group("TRandom")]
#[TestDox("TRandom")]
class TRandomTest extends TestCase
{
    private TRandom $stub;

    public function setUp(): void
    {
        $this->stub = new TRandom();
    }

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
            $rnd = $this->stub->number($min, $max);
            $this->assertIsInt($rnd);
            $this->assertTrue($rnd >= $min && $rnd <= $max);
        }
    }


    #[TestDox("number: Should swap min and max args when min is greater than max")]
    public function testShouldSwapMinAndMaxArgs()
    {
        $min = 1000;
        $max = 500;
        $rnd = $this->stub->number($min, $max);
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
            $rnd = $this->stub->lzNumber($min, $max, $len);
            $this->assertEquals($len, strlen($rnd));
        }
    }


    #[TestDox("lzNumber: Should fix length arg when null or lesser than max value length.")]
    public function testShouldFixLength()
    {
        $rnd = $this->stub->lzNumber(0, 9999, null);
        $this->assertEquals(4, strlen($rnd));

        $rnd = $this->stub->lzNumber(0, 9999, 3);
        $this->assertEquals(4, strlen($rnd));
    }


    #[TestDox("lzNumber: Should throw InvalidArgumentException when min or max args are negative")]
    public function testShouldThrowWhenMinOrMaxArgsAreInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("(TRandom::lzNumber) The min and max arguments must be positive integers.");
        $this->stub->lzNumber(-200, -100, 3);
    }


    #[TestDox("string: Should generate a random string with default chars")]
    public function testShouldGenerateWithDefaultChars()
    {
        $lenghts = [5, 10, 20, 30, 47, 54];
        foreach ($lenghts as $len) {
            $rnd = $this->stub->string($len);
            $this->assertEquals($len, strlen($rnd));
        }
    }


    #[TestDox("Should generate a random string with default parameters")]
    public function testShouldGenerateWithDefaultParameters()
    {
        $pattern = "/^[0-9a-zA-Z!;#%&\(\)*+,\-.\/:;<=>?@\[\]\^_{|}~].+$/";

        $sizes = [5, 10, 30, 50, 100];

        foreach ($sizes as $size) {
            $str = $this->stub->string($size);
            $this->assertEquals($size, strlen($str));
            $this->assertMatchesRegularExpression($pattern, $str);
        }
    }


    #[TestDox("Should generate a random string without symbols")]
    public function testShouldGenerateWithoutSymbols()
    {
        $pattern = "/^[0-9a-zA-Z].+$/";
        $sizes = [5, 10, 30, 50, 100];

        $this->stub->includeSymbols = false;
        foreach ($sizes as $size) {
            $str = $this->stub->string($size);
            $this->assertEquals($size, strlen($str));
            $this->assertMatchesRegularExpression($pattern, $str);
        }
    }


    #[TestDox("Should generate a random string without numbers")]
    public function testShouldGenerateWithoutNumbers()
    {
        $pattern = "/^[a-zA-Z!;#%&\(\)*+,\-.\/:;<=>?@\[\]\^_{|}~].+$/";
        $sizes = [5, 10, 30, 50, 100];

        $this->stub->includeNumbers = false;
        foreach ($sizes as $size) {
            $str = $this->stub->string($size);
            $this->assertEquals($size, strlen($str));
            $this->assertMatchesRegularExpression($pattern, $str);
        }
    }


    #[TestDox("Should generate a random string without alpha chars")]
    public function testShouldGenerateWithoutAlphabeticalChars()
    {
        $pattern = "/^[0-9!;#%&\(\)*+,\-.\/:;<=>?@\[\]\^_{|}~].+$/";
        $sizes = [5, 10, 30, 50, 100];

        $this->stub->includeAlpha = false;
        foreach ($sizes as $size) {
            $str = $this->stub->string($size);
            $this->assertEquals($size, strlen($str));
            $this->assertMatchesRegularExpression($pattern, $str);
        }
    }


    #[TestDox("Should throw exception when all parameters are disabled")]
    public function testShouldThrowWhenAllParamsAreDisabled()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Could not generate a randomic string, please check class parameters.");
        $this->stub->includeAlpha = false;
        $this->stub->includeNumbers = false;
        $this->stub->includeSymbols = false;

        $this->stub->string(10);
    }


    #[TestDox("Should throw InvalidArgumentException when length is lesser than one")]
    public function testShouldThrowWhenLengthIsLesserThanOne()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The length argument must be greater than zero.");
        $this->stub->string(0);
    }


    #[TestDox("Should generate a string with custom characters")]
    public function testShouldGenerateAStringWithCustomCharacters()
    {
        $this->stub->alpha = "ABCDEF";
        $this->stub->includeSymbols = false;
        $str = $this->stub->string(20);
        $this->assertMatchesRegularExpression("/^[A-F0-9]{20}$/", $str);
    }


    #[TestDox("Should start a random string with alphabetical character")]
    public function testShouldStartWithAplhaChar()
    {
        $this->stub->alpha = "ABCDEF";
        $this->stub->includeAlpha = true;
        $this->stub->includeNumbers = true;
        $this->stub->includeSymbols = true;
        $this->stub->startWithAlphaChar = true;

        for ($i = 0; $i < 100; $i++) {
            $str = $this->stub->string(10);
            $this->assertMatchesRegularExpression("/^[A-F]/", $str);
        }
    }
}
