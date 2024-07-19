<?php declare(strict_types=1);

namespace Tests\SemVer;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Torugo\Util\SemVer\Enums\VersionComparison;
use Torugo\Util\SemVer\SemVer;

#[Group("Version")]
#[TestDox("Version")]
class SemVerTest extends TestCase
{
    #[TestDox("Should extract version parts correctly")]
    public function testShouldBeValid()
    {
        $version = new SemVer("1.1.0-alpha+134");
        $this->assertEquals(1, $version->major);
        $this->assertEquals(1, $version->minor);
        $this->assertEquals(0, $version->patch);
        $this->assertEquals('alpha', $version->preRelease);
        $this->assertEquals(134, $version->build);
    }


    #[TestDox("Should be smaller")]
    public function testShouldBeSmaller()
    {
        $versions = [
            ["0.0.1", "1.0.1"],
            ["1.0.0", "1.0.1"],
            ["1.0.9", "1.1.0"],
            ["1.0.0", "1.1.0"],
            ["1.0.0", "2.0.0"],
            ["1.0.0-alpha", "1.0.0-alpha.1"],
            ["1.0.0-alpha.1", "1.0.0-alpha.2"],
            ["1.0.0-alpha", "1.0.0-beta"],
            ["1.0.0-alpha.1", "1.0.0-beta"],
            ["1.0.0-beta", "1.0.0-beta.1"],
            ["1.0.0-beta.1", "1.0.0-beta.2"],
            ["1.0.0-beta", "1.0.0-rc"],
            ["1.0.0-beta.1", "1.0.0-rc"],
            ["1.0.0-rc", "1.0.0-rc.1"],
            ["1.0.0-rc", "1.0.0"],
            ["1.0.0-rc.1", "1.0.0"],
            ["1.0.0+11", "1.0.0+12"],
            ["1.0.0-beta.2+167", "1.0.0-beta.2+234"],
        ];

        foreach ($versions as $v) {
            $version = new SemVer($v[0]);
            $this->assertEquals(VersionComparison::Smaller, $version->compareTo($v[1]));
        }
    }


    #[TestDox("Should be bigger")]
    public function testShouldBeBigger()
    {
        $versions = [
            ["1.0.1", "1.0.0"],
            ["1.1.0", "1.0.0"],
            ["2.0.0", "1.0.0"],
            ["1.0.0-alpha.1", "1.0.0-alpha"],
            ["1.0.0-alpha.2", "1.0.0-alpha.1"],
            ["1.0.0-beta", "1.0.0-alpha"],
            ["1.0.0-beta", "1.0.0-alpha.1"],
            ["1.0.0-beta.1", "1.0.0-beta"],
            ["1.0.0-beta.2", "1.0.0-beta.1"],
            ["1.0.0-rc", "1.0.0-beta"],
            ["1.0.0-rc", "1.0.0-beta.1"],
            ["1.0.0-rc.1", "1.0.0-rc"],
            ["1.0.0", "1.0.0-rc"],
            ["1.0.0", "1.0.0-rc.1"],
            ["3.2.6+145", "3.2.6+123"],
            ["1.0.0+600", "1.0.0-alpha.1+650"],
            ["1.0.0-alpha.2+988", "1.0.0-alpha.2+987"],
        ];

        foreach ($versions as $v) {
            $version = new SemVer($v[0]);
            $this->assertEquals(VersionComparison::Bigger, $version->compareTo($v[1]));
        }
    }


    #[TestDox("Should be equal")]
    public function testShouldBeEqual()
    {
        $versions = [
            ["1.0.0", "1.0.0"],
            ["1.0.1", "1.0.1"],
            ["1.1.0", "1.1.0"],
            ["1.1.1", "1.1.1"],
            ["1.1.1+13", "1.1.1+13"],
            ["1.0.0-alpha", "1.0.0-alpha"],
            ["1.0.0-alpha.1", "1.0.0-alpha.1"],
            ["1.0.0-beta", "1.0.0-beta"],
            ["1.0.0-beta.1", "1.0.0-beta.1"],
            ["1.0.0-beta.2", "1.0.0-beta.2"],
            ["1.0.0-rc", "1.0.0-rc"],
            ["1.0.0-rc.1", "1.0.0-rc.1"],
            ["1.0.0-alpha.2+988", "1.0.0-alpha.2+988"],
        ];

        foreach ($versions as $v) {
            $version = new SemVer($v[0]);
            $this->assertEquals(VersionComparison::Equal, $version->compareTo($v[1]));
        }
    }


    #[TestDox("Should throw InvalidArgumentException on invalid version number")]
    public function testThrowOnInvalidVersion()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid version number '1.0'.");
        new SemVer("1.0");
    }
}
