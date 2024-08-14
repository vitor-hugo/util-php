<?php declare(strict_types=1);

namespace Tests\TFile;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\WithoutErrorHandler;
use PHPUnit\Framework\TestCase;
use Torugo\Util\TFile\TFile;

#[Group("TFile")]
#[TestDox("TFile")]
class TFileTest extends TestCase
{

    #[TestDox("Should return true when file exist")]
    public function testShouldReturnTrueWhenFileExist()
    {
        $this->assertTrue(TFile::exists(__DIR__ . "/TestFiles/.env"));
        $this->assertTrue(TFile::exists(__DIR__ . "/TestFiles/source.json"));
    }


    #[TestDox("Should return false when file not exist")]
    #[WithoutErrorHandler()]
    public function testShouldReturnFalseWhenFileNotExist()
    {
        $this->assertFalse(TFile::exists(__DIR__ . "/TestFiles/env.txt"));
        $this->assertFalse(TFile::exists(__DIR__ . "/TestFiles/ source.json"));
    }


    #[TestDox("Should create file if not exists")]
    public function testShouldCreateFileIfNotExists()
    {
        $this->assertTrue(TFile::exists(__DIR__ . "/TestFiles/.test", true));
        unlink(__DIR__ . "/TestFiles/.test");
    }


    #[TestDox("Should return false when cannot create a file")]
    public function testShouldReturnFalseWhenCannotCreateAFile()
    {
        $this->assertFalse(TFile::create(__DIR__ . "/TestFiles/readonly.txt"));
    }


    #[TestDox("Should instanciate TFile correctly when file exists")]
    public function test()
    {
        $file1 = new TFile(__DIR__ . "/TestFiles/source.json");
        $this->assertInstanceOf(TFile::class, $file1);

        $file2 = new TFile(__DIR__ . "/TestFiles/.env");
        $this->assertInstanceOf(TFile::class, $file2);
    }


    #[TestDox("Should throw InvalidArgumentException when file not exist")]
    #[WithoutErrorHandler()]
    public function testShouldThrowWhenFileNotExist()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("TFile: The file does not exist or is not readable.");
        new TFile(__DIR__ . "/.env");
    }


    #[TestDox("Should load env file content as associative array")]
    #[WithoutErrorHandler()]
    public function testShouldParseEnvFile()
    {
        $file = new TFile(__DIR__ . "/TestFiles/.env");
        $env = $file->parseEnv();

        $this->assertArrayHasKey("DB_ADDRESS", $env);
        $this->assertArrayHasKey("DB_PORT", $env);
        $this->assertArrayHasKey("DB_NAME", $env);
        $this->assertArrayHasKey("DB_USER", $env);
        $this->assertArrayHasKey("DB_PASS", $env);
        $this->assertArrayHasKey("EMPTY", $env);

        $this->assertArrayNotHasKey("INVALID_KEY", $env);
        $this->assertArrayNotHasKey("ALSO-INVALID", $env);

        $this->assertEquals("localhost", $env["DB_ADDRESS"]);
        $this->assertEquals("3306", $env["DB_PORT"]);
        $this->assertEquals("TORUGO", $env["DB_NAME"]);
        $this->assertEquals("UTORUGO", $env["DB_USER"]);
        $this->assertEquals("MySup3rStr0ngP4ssw0rd!", $env["DB_PASS"]);
        $this->assertEquals("", $env["EMPTY"]);
    }


    #[TestDox("Should load json file content as associative array")]
    #[WithoutErrorHandler()]
    public function testShouldParseJsonFile()
    {
        $file = new TFile(__DIR__ . "/TestFiles/source.json");
        $json = $file->parseJson();

        $this->assertIsArray($json);

        $this->assertEquals("localhost", $json["dbAddress"]);
        $this->assertEquals(3306, $json["dbPort"]);
        $this->assertEquals("TORUGO", $json["dbName"]);
        $this->assertEquals("UTORUGO", $json["dbUser"]);
        $this->assertEquals("MySup3rStr0ngP4ssw0rd!", $json["dbPass"]);
        $this->assertEquals("Author's Name", $json["author"]["name"]);
        $this->assertEquals("author@host.com", $json["author"]["email"]);
        $this->assertEquals("https://github.com/author", $json["author"]["url"]);
    }


    #[TestDox("Should return an empty array when json is invalid")]
    #[WithoutErrorHandler()]
    public function testShouldReturnAnEmptyArrayWhenJsonIsInvalid()
    {
        $file = new TFile(__DIR__ . "/TestFiles/empty.txt");
        $json = $file->parseJson();
        $this->assertEquals([], $json);
    }


    #[TestDox("Should return true when file is writable")]
    public function testShouldReturnTrueWhenFileIsWritable()
    {
        $file = new TFile(__DIR__ . "/TestFiles/empty.txt");
        $this->assertTrue($file->isWritable());
    }


    #[TestDox("Should return false when file is not writable")]
    public function testShouldReturnFalseWhenFileIsNotWritable()
    {
        $file = new TFile(__DIR__ . "/TestFiles/readonly.txt");
        $this->assertFalse($file->isWritable());
    }


    #[TestDox("Should parse KEY files")]
    public function testShouldParaseKeyFiles()
    {
        $key = "qtTPBKmLCFkxhhJdfLdHBFBVHOnZqSYuxXlkwICrFQoxppjeOhECPZNx";
        $key .= "JwCGfbaKkcHouFTEtpGqZMslvpMtpmZrkmxfPUtmgOCkWoXRaxCceWZU";
        $key .= "SjoWhTLTfLjTuuArXFgSRkXHBRZYvIAOxBVfcLjEsRuNzdrhpsFoYDkT";
        $key .= "OnJlmMQBFpHPbgxfgQZqmIQtbxuFpKYdTSGvtRLNEWeneGFnbMPhUZBR";
        $key .= "MsPnAQdykmbSHZcFOjRFocTrtjblReAkHyOliTzNrvjAtjNHLrtowfJk";
        $key .= "NcHwSzLkRLACJKfzVnRqOjXKskGmVikEfFFwzlpNpQnPUrTvebkqtyfZ";
        $key .= "KsjKQszuDBUjkfvXKEbRXTrGmzjpqVIlqGVyEzOHanLnFskwSmyFMebD";
        $key .= "JMzLRlODkZcdKSggzObSKfsVoTcwBCIULcZkGblMJuelyafhOEcTcECj";
        $key .= "blZEFhNYfLSbOvuDXFlmMlJkJMjrWAKEHNsYIpAjhglOEkqKIAoRNueu";
        $key .= "GFOhfxvteQkRIGpoXjknjPtwAQZgiWdINfsfNWRoeFRygPsKSZVksWYd";

        $file = new TFile(__DIR__ . "/TestFiles/token.key");
        $this->assertEquals($key, $file->parseKeyFile());
    }


    #[TestDox("Should throw Exception when trying to parse an invalid KEY file")]
    public function testShouldThrowWhenTryingToParseAnInvalidKeyFile()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid KEY file.");
        $file = new TFile(__DIR__ . "/TestFiles/source.json");
        $file->parseKeyFile();
    }
}
