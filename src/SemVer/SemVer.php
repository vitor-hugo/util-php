<?php declare(strict_types=1);

namespace Torugo\Util\SemVer;

use InvalidArgumentException;
use Torugo\Util\SemVer\Enums\VersionComparison;

/**
 * Validates and compare semantic version numbers.
 * The version number must follow [semver.org rules](https://semver.org)
 */
class SemVer
{
    private string $_version;
    public int $major = 0;
    public int $minor = 0;
    public int $patch = 0;
    public string $preRelease = "";
    public int $build = 0;

    private const PATTERN = "/^(?P<major>0|[1-9]\d*)\.(?P<minor>0|[1-9]\d*)\.(?P<patch>0|[1-9]\d*)(?:-(?P<prerelease>(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+(?P<buildmetadata>[0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$/";


    public function getVersion(): string
    {
        return $this->_version;
    }

    public function setVersion(string $version): void
    {
        $this->_version = $version;
        $this->initVersionProps();
    }

    /**
     * Validates and compare version numbers.
     * @param string $version Version number MUST follow [semver.org rules](https://semver.org)
     */
    public function __construct(string $version)
    {
        $this->_version = $version;
        $this->initVersionProps();
    }

    private function initVersionProps(): void
    {
        $parts = $this->extractVersionParts($this->_version);

        $this->major = $parts["major"];
        $this->minor = $parts["minor"];
        $this->patch = $parts["patch"];
        $this->preRelease = (string) $parts["preRelease"];
        $this->build = $parts["build"];
    }


    private function extractVersionParts(string $version): array
    {
        $matches = [];

        $test = preg_match(self::PATTERN, $version, $matches);

        if ($test === 0 || $test === false) {
            throw new InvalidArgumentException("Invalid version number '$version'.");
        }

        return [
            "major" => (int) $matches["major"],
            "minor" => (int) $matches["minor"] ?? 0,
            "patch" => (int) $matches["patch"] ?? 0,
            "preRelease" => strtolower($matches["prerelease"] ?? ""),
            "build" => (int) ($matches["buildmetadata"] ?? 0),
        ];
    }


    public function compareTo(string $version): VersionComparison
    {
        $compared = $this->extractVersionParts($version);

        $versionNumbers = ["major", "minor", "patch"];
        $comparison = VersionComparison::Equal;
        foreach ($compared as $key => $value) {
            if (in_array($key, $versionNumbers)) {
                $vNumber = $this->{$key} <=> $value;
                if ($vNumber != 0) {
                    $comparison = VersionComparison::tryFrom($vNumber);
                    break;
                }
                continue;
            }

            if ($key == "preRelease") {
                $comp = $this->comparePreReleaseVersion((string) $value);
                if ($comp != VersionComparison::Equal) {
                    $comparison = $comp;
                    break;
                }
            }

            if ($key == "build") {
                $comp = $this->build <=> $value;
                $comparison = VersionComparison::tryFrom($comp);
                break;
            }
        }

        return $comparison;
    }


    private function comparePreReleaseVersion(string $pre = ""): VersionComparison
    {
        $labels = [
            "alpha" => 1,
            "beta" => 2,
            "rc" => 3,
        ];

        $mainLabel = preg_replace("/[^a-z]/", "", $this->preRelease);
        $outLabel = preg_replace("/[^a-z]/", "", $pre);

        $main = $labels[$mainLabel] ?? 4;
        $out = $labels[$outLabel] ?? 4;

        $comp = $main <=> $out;

        if ($comp != 0) {
            return VersionComparison::tryFrom($comp);
        }

        $preVer = preg_replace("/[^0-9]+/", "", $this->preRelease) ?? 0;
        $outVer = preg_replace("/[^0-9]+/", "", $pre) ?? 0;

        return VersionComparison::tryFrom($preVer <=> $outVer);
    }
}
