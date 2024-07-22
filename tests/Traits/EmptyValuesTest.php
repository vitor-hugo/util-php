<?php declare(strict_types=1);

namespace Tests\Traits;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Torugo\Util\Traits\EmptyValues;

#[Group("Traits")]
#[Group("EmptyValues")]
#[TestDox("EmptyValues")]
class EmptyValuesTest extends TestCase
{
    use EmptyValues;

    #[TestDox("Should return an empty value for each variable type")]
    public function testShouldReturnAnEmptyValue()
    {
        $types = [
            "array" => [],
            "boolean" => false,
            "double" => 0.0,
            "integer" => 0,
            "string" => "",
            "mixed" => null,
        ];

        foreach ($types as $type => $expected) {
            $this->assertEquals($expected, $this->getEmptyValueForType($type));
        }

        $empty = $this->getEmptyValueForType("object");
        $this->assertTrue((new \ReflectionClass($empty))->isAnonymous());
    }
}
