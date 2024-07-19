<?php declare(strict_types=1);

namespace Torugo\Util\SemVer\Enums;

enum VersionComparison: int
{
    case Smaller = -1;
    case Equal = 0;
    case Bigger = 1;
}
