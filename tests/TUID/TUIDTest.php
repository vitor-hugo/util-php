<?php declare(strict_types=1);

namespace Tests\TUID;

use DateTime;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Torugo\Util\TUID\TUID;

#[Group("TUID")]
#[TestDox("TUID")]
class TUIDTest extends TestCase
{
    #[TestDox("Should generate a SHORT TUID")]
    public function testShouldGenerateShort()
    {
        $tuid = TUID::short();
        $this->assertEquals(20, strlen($tuid));
        $this->assertTrue(TUID::validate($tuid));
    }


    #[TestDox("Should generate a MEDIUM TUID")]
    public function testShouldGenerateMedium()
    {
        $tuid = TUID::medium();
        $this->assertEquals(26, strlen($tuid));
        $this->assertTrue(TUID::validate($tuid));
    }


    #[TestDox("Should generate a LONG TUID")]
    public function testShouldGenerateLong()
    {
        $tuid = TUID::long();
        $this->assertEquals(36, strlen($tuid));
        $this->assertTrue(TUID::validate($tuid));
    }


    #[TestDox("Should be invalid TUID")]
    public function testShouldBeInvalid()
    {
        //        Should be 'TS'
        //              ||
        $tuid = "Q0RTBAW-TX0SHUIBP4QS";
        $this->assertFalse(TUID::validate($tuid));

        //              Should be 'TM'
        //                    ||
        $tuid = "Y57WW6D6-T8KH-TX0SHUICT2FQ";
        $this->assertFalse(TUID::validate($tuid));

        //                        Should be 'TL'
        //                              ||
        $tuid = "UWIZ248Q-UT2V-6EN8QN2VT-TX0SHUIF31SS";
        $this->assertFalse(TUID::validate($tuid));


        // Too long
        $tuid = "UWIZ248Q-UT2V-6EN8QN2VT-TX0SHUIF31SSX";
        $this->assertFalse(TUID::validate($tuid));

        // Too short
        $tuid = "Q0RTBAW-TS0SHUIBP4Q";
        $this->assertFalse(TUID::validate($tuid));

    }


    #[TestDox("Should extract DateTime object from TUID")]
    public function testShouldExtractDateTime()
    {
        $s = TUID::short();
        $m = TUID::medium();
        $l = TUID::long();

        $current = new DateTime("now");

        $dt = TUID::getDateTime($s);
        $diff = $dt->diff($current);
        $this->assertTrue($diff->s < 1 && $diff->f < 1);


        $dt = TUID::getDateTime($m);
        $diff = $dt->diff($current);
        $this->assertTrue($diff->s < 1 && $diff->f < 1);


        $dt = TUID::getDateTime($l);
        $diff = $dt->diff($current);
        $this->assertTrue($diff->s < 1 && $diff->f < 1);
    }


    #[TestDox("Should return false when trying to extract DateTime")]
    public function testShouldReturnFalse()
    {
        $tuid = "Q0RTBAW-TX0SHUIBP4QS";
        $this->assertFalse(TUID::getDateTime($tuid));
    }
}
