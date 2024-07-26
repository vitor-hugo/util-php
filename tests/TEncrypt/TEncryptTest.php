<?php declare(strict_types=1);

namespace Tests\TEncrypt;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\WithoutErrorHandler;
use PHPUnit\Framework\TestCase;
use Torugo\Util\TBase64\TBase64;
use Torugo\Util\TEncrypt\Enums\TCipher;
use Torugo\Util\TEncrypt\TEncrypt;

#[Group("TEncrypt")]
#[TestDox("TEncrypt")]
class TEncryptTest extends TestCase
{
    private string $key = "ye-PaJYnFPluROpIFo146zhQNvKHbUkIKNMc2rkd8rE";

    #[TestDox("Should encrypt a value with default cipher method")]
    public function testShouldEncryptAValueWithDefaultCipher(): string
    {
        $enc = TEncrypt::encrypt("May the force be with you!", $this->key);
        $this->assertNotEmpty($enc);
        return $enc;
    }


    #[Depends("testShouldEncryptAValueWithDefaultCipher")]
    #[TestDox("Should decrypt the encrypted value")]
    public function testShouldDecryptAnEncryptedValue(string $enc): void
    {
        $str = TEncrypt::decrypt($enc, $this->key);
        $this->assertEquals("May the force be with you!", $str);
    }


    #[TestDox("Should encrypt and decrypt with all available cipher methods")]
    public function testShouldEncryptAndDecryptWithAllCipherMethods(): void
    {
        $ciphers = TCipher::cases();

        $text = "My precious!";

        foreach ($ciphers as $cipher) {
            $key = TEncrypt::generateKeyForCipher($cipher);
            TEncrypt::setCipher($cipher);
            $enc = TEncrypt::encrypt($text, $key);
            $dec = TEncrypt::decrypt($enc, $key);
            // echo "\n$cipher->value - $key";

            $this->assertEquals($cipher, TCipher::fromString($cipher->value));
            $this->assertNotEmpty($enc);
            $this->assertEquals($text, $dec);
            $this->assertEquals(TEncrypt::getCipher(), $cipher);
        }
    }


    #[TestDox("Should return an empty string when trying to encrypt or decrypt empty strings")]
    public function testShouldReturnEmptyString()
    {
        $this->assertEmpty(TEncrypt::encrypt("", $this->key));
        $this->assertEmpty(TEncrypt::decrypt("", $this->key));
    }


    #[TestDox("Should throw InvalidArgumentException when defining a invalid key")]
    public function testShouldThrowOnInvalidKey()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("TEncrypt: Invalid key for cipher 'aes-256-cfb', it should be at least 32 bytes long.");
        TEncrypt::setCipher(TCipher::AES_256_CFB);
        TEncrypt::encrypt("Value", "EE7BRCM6HnG1Hkqq0BwCb5Aa5k");
    }


    #[TestDox("Should throw InvalidArgumentException when to set an invalid cipher method")]
    public function testShouldThrowWhenTryingToSetAnInvalidCipher()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("'invalid' is a invalid cipher method.");
        TCipher::fromString("INVALID");
    }


    #[TestDox("Should throw Exception when trying to decrypt an invalid data")]
    public function testShouldThrowWhenDecryptingInvalidData1()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("TEncrypt: Invalid encrypted string.");

        $data = TBase64::encode("abc.def.ghi");
        TEncrypt::decrypt($data, $this->key);
    }


    #[TestDox("Should throw Exception when trying to decrypt with invalid IV")]
    public function testShouldThrowWhenDecryptingInvalidIV()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("TEncrypt: Could not decrypt the data.");

        TEncrypt::setCipher(TCipher::SM4_CBC);
        $data = TEncrypt::encrypt("String", "UN7tFFxBlRRiMh5ycXWIew");

        // Changing 'iv' to force openssl to return false
        $data = explode(".", $data);
        $data[1] = "x";
        $data = implode(".", $data);

        TEncrypt::decrypt($data, "UN7tFFxBlRRiMh5ycXWIew");
    }


    #[TestDox("Should throw Exception when trying to generate a too long key")]
    public function testShouldThrowExceptionWhenEncryptingWithInvalidKeyLength()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("TEncrypt: Invalid byte size");

        $key = TEncrypt::generateKey(PHP_INT_MAX);
        TEncrypt::setCipher(TCipher::SM4_CBC);
        TEncrypt::encrypt("String", "8MClFSfXWpUu_ilAV2DnXg");
    }
}
