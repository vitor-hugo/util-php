<?php declare(strict_types=1);

namespace Tests\TBase64;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Torugo\Util\TBase64\TBase64;

#[Group("TBase64")]
#[TestDox("TBase64")]
class TBase64Test extends TestCase
{
    #[TestDox("Should encode a string to Base64 url safe")]
    public function testShouldEncodeToTBase64Safe(): void
    {
        $str = "abcdef ÃÁÀÂÄÇÉÊËÍÏÕÓÔÖÚÜ 1234567890 !@#$%^&*(){}[]";
        $b64 = TBase64::encode($str);
        $this->assertEquals("YWJjZGVmIMODw4HDgMOCw4TDh8OJw4rDi8ONw4_DlcOTw5TDlsOaw5wgMTIzNDU2Nzg5MCAhQCMkJV4mKigpe31bXQ", $b64);
    }

    #[TestDox("Should decode Base64 url safe strings")]
    public function testShouldDecodeTBase64SafeStrings(): void
    {
        $b64 = "YWJjZGVmIMODw4HDgMOCw4TDh8OJw4rDi8ONw4_DlcOTw5TDlsOaw5wgMTIzNDU2Nzg5MCAhQCMkJV4mKigpe31bXQ";
        $str = TBase64::decode($b64);
        $this->assertEquals("abcdef ÃÁÀÂÄÇÉÊËÍÏÕÓÔÖÚÜ 1234567890 !@#$%^&*(){}[]", $str);
    }

    #[TestDox("Should return empty string when receives an empty string")]
    public function testShouldReturnEmptyString(): void
    {
        $this->assertEquals("", TBase64::encode(""));
        $this->assertEquals("", TBase64::decode(""));
    }
}
