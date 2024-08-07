<?php declare(strict_types=1);

namespace Tests\TID;

use DateTime;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Torugo\Util\TID\TID;

#[Group("TID")]
#[TestDox("TID")]
class TIDTest extends TestCase
{
    #[TestDox("Should generate a SHORT TID")]
    public function testShouldGenerateShort()
    {
        $tid = TID::short();
        $this->assertEquals(20, strlen($tid));
        $this->assertTrue(TID::validate($tid));
    }


    #[TestDox("Should generate a MEDIUM TID")]
    public function testShouldGenerateMedium()
    {
        $tid = TID::medium();
        $this->assertEquals(26, strlen($tid));
        $this->assertTrue(TID::validate($tid));
    }


    #[TestDox("Should generate a LONG TID")]
    public function testShouldGenerateLong()
    {
        $tid = TID::long();
        $this->assertEquals(36, strlen($tid));
        $this->assertTrue(TID::validate($tid));
    }


    #[TestDox("Should be invalid TID")]
    public function testShouldBeInvalid()
    {
        //        Should be 'TS'
        //              ||
        $tid = "Q0RTBAW-TX0SHUIBP4QS";
        $this->assertFalse(TID::validate($tid));

        //              Should be 'TM'
        //                    ||
        $tid = "Y57WW6D6-T8KH-TX0SHUICT2FQ";
        $this->assertFalse(TID::validate($tid));

        //                        Should be 'TL'
        //                              ||
        $tid = "UWIZ248Q-UT2V-6EN8QN2VT-TX0SHUIF31SS";
        $this->assertFalse(TID::validate($tid));


        // Too long
        $tid = "UWIZ248Q-UT2V-6EN8QN2VT-TX0SHUIF31SSX";
        $this->assertFalse(TID::validate($tid));

        // Too short
        $tid = "Q0RTBAW-TS0SHUIBP4Q";
        $this->assertFalse(TID::validate($tid));

    }


    #[TestDox("Should extract DateTime object from TID")]
    public function testShouldExtractDateTime()
    {
        $s = TID::short();
        $m = TID::medium();
        $l = TID::long();

        $current = new DateTime("now");

        $dt = TID::getDateTime($s);
        $diff = $dt->diff($current);
        $this->assertTrue($diff->s < 1 && $diff->f < 1);


        $dt = TID::getDateTime($m);
        $diff = $dt->diff($current);
        $this->assertTrue($diff->s < 1 && $diff->f < 1);


        $dt = TID::getDateTime($l);
        $diff = $dt->diff($current);
        $this->assertTrue($diff->s < 1 && $diff->f < 1);
    }


    #[TestDox("Should return false when trying to extract DateTime")]
    public function testShouldReturnFalse()
    {
        $tid = "Q0RTBAW-TX0SHUIBP4QS";
        $this->assertFalse(TID::getDateTime($tid));
    }
}
