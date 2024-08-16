<?php declare(strict_types=1);

namespace Tests\TPass;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Torugo\Util\TPass\TPass;

#[Group("TPass")]
#[TestDox("TPass")]
class TPassTest extends TestCase
{
    public TPass $stub;

    public function setUp(): void
    {
        $this->stub = new TPass;
    }


    #[TestDox("Should generate a random password string with default options")]
    public function testShouldGenerateRandomPassWithDefaultOptions()
    {
        $pass = $this->stub->generate(64);
        $this->assertIsString($pass);
        $this->assertEquals(64, strlen($pass));
    }


    #[TestDox("Password should begin with a letter")]
    public function testPassShouldBeginWithALetter()
    {
        $this->stub->beginWithALetter = true;
        for ($i = 0; $i < 20; $i++) {
            $pass = $this->stub->generate(20);
            $this->assertMatchesRegularExpression("/^[a-zA-Z]/", $pass);
        }
    }


    #[TestDox("Password should with any character")]
    public function testPassShouldBeginWithAnyChar()
    {
        $this->stub->beginWithALetter = false;
        for ($i = 0; $i < 20; $i++) {
            $pass = $this->stub->generate(20);
            $this->assertEquals(20, strlen($pass));
            $this->assertMatchesRegularExpression(
                "/^[a-zA-Z0-9\!\;\#\$\%\&\(\)\*\+\,\-\.\/\:\;\<\=\>\?\@\[\]\^\_\{\|\}\~]/",
                $pass
            );
        }
    }


    #[TestDox("Password should have only lowercased letters")]
    public function testPassShouldHaveOnlyLowercasedLetters()
    {
        $this->stub->includeLowercase = true;
        $this->stub->includeUppercase = false;
        $this->stub->includeNumbers = false;
        $this->stub->includeSymbols = false;
        $this->stub->beginWithALetter = false;

        for ($i = 0; $i < 20; $i++) {
            $pass = $this->stub->generate(20);
            $this->assertMatchesRegularExpression("/^[a-z].+$/", $pass);
        }
    }


    #[TestDox("Password should have only uppercased letters")]
    public function testPassShouldHaveOnlyUppercasedLetters()
    {
        $this->stub->includeLowercase = false;
        $this->stub->includeUppercase = true;
        $this->stub->includeNumbers = false;
        $this->stub->includeSymbols = false;
        $this->stub->beginWithALetter = false;

        for ($i = 0; $i < 20; $i++) {
            $pass = $this->stub->generate(20);
            $this->assertMatchesRegularExpression("/^[A-Z].+$/", $pass);
        }
    }


    #[TestDox("Password should have only numeric characters")]
    public function testPassShouldHaveOnlyNumbers()
    {
        $this->stub->includeLowercase = false;
        $this->stub->includeUppercase = false;
        $this->stub->includeNumbers = true;
        $this->stub->includeSymbols = false;
        $this->stub->beginWithALetter = false;

        for ($i = 0; $i < 20; $i++) {
            $pass = $this->stub->generate(20);
            $this->assertMatchesRegularExpression("/^[0-9].+$/", $pass);
        }
    }


    #[TestDox("Password should have only special characters")]
    public function testPassShouldHaveOnlySpecialChars()
    {
        $this->stub->includeLowercase = false;
        $this->stub->includeUppercase = false;
        $this->stub->includeNumbers = false;
        $this->stub->includeSymbols = true;
        $this->stub->beginWithALetter = false;

        for ($i = 0; $i < 20; $i++) {
            $pass = $this->stub->generate(20);
            $this->assertMatchesRegularExpression("/^[\!\;\#\$\%\&\(\)\*\+\,\-\.\/\:\;\<\=\>\?\@\[\]\^\_\{\|\}\~].+$/", $pass);
        }
    }


    #[TestDox("Should check password strength")]
    public function testShouldCheckPasswordStrength()
    {
        $passwords = [
            "123456" => 0,
            "112233" => 0,
            "admin" => 0,
            "password" => 0,
            "senha" => 0,
            "mudar12345" => 0,
            "sis1223!A" => 1,
            "NU$;K^9" => 2,
            "NU$;k3+" => 3,
            "Password123!NU$;k3+" => 4,
            "NU$;K^+B#D!(;+D%8nP}" => 4,
            "NU$;K^+ B#D!(;+D%8nP}" => 4,
            "NU$;K^+B#D!(;+D%8nP}123456" => 4,
        ];

        foreach ($passwords as $pass => $expectedScore) {
            $this->assertEquals(
                $expectedScore,
                $this->stub->checkPasswordStrength((string) $pass)
            );
        }
    }


    #[TestDox("Should throw InvalidArgumentException when disabling all options")]
    public function testShouldThrowWhenDisablingAllOptions()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Can't generate a password, all source characters are disabled!");
        $this->stub->includeLowercase = false;
        $this->stub->includeUppercase = false;
        $this->stub->includeNumbers = false;
        $this->stub->includeSymbols = false;

        $this->stub->generate(10);
    }


    #[TestDox("Should throw InvalidArgumentException when enabling beginWithALetter and all letters disabled")]
    public function testShouldThrowWhenEnablingBeginWithALetterWithoutAnyLetterEnabled()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Can't begin with a letter when Lowercase and Uppercase properties are disabled!");
        $this->stub->includeLowercase = false;
        $this->stub->includeUppercase = false;
        $this->stub->includeNumbers = true;
        $this->stub->includeSymbols = true;
        $this->stub->beginWithALetter = true;

        $this->stub->generate(10);
    }


    #[TestDox("Should set symbols source")]
    public function testShouldSetSymbolsSource()
    {
        $this->stub->setSymbols("ABCDEF1234567890");
        $this->stub->includeLowercase = false;
        $this->stub->includeUppercase = false;
        $this->stub->includeNumbers = false;
        $this->stub->includeSymbols = true;
        $this->stub->beginWithALetter = false;

        $pass = $this->stub->generate(10);
        $this->assertMatchesRegularExpression("/^[A-F0-9].+$/", $pass);
    }

    #[TestDox("Should do nothing when trying to set symbols with an empty string")]
    public function testShouldDoNothing()
    {
        $this->stub->includeLowercase = false;
        $this->stub->includeUppercase = false;
        $this->stub->includeNumbers = false;
        $this->stub->includeSymbols = true;
        $this->stub->beginWithALetter = false;

        $this->stub->setSymbols("ABCDEF1234567890");
        $this->stub->setSymbols(" ");
        $pass = $this->stub->generate(10);
        $this->assertMatchesRegularExpression("/^[A-F0-9].+$/", $pass);
    }
}
