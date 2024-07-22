<?php declare(strict_types=1);

namespace Tests\Traits;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Torugo\Util\Traits\FromArrayFactory;

class StubClass
{
    use FromArrayFactory;

    public string $str;
    public int $int;
    public float $float;
    public bool $bool;
    public array $array;
    public mixed $mixed;
    public string $name;
}

#[Group("Traits")]
#[Group("FromArrayFactory")]
#[TestDox("FromArrayFactory")]
class FromArrayFactoryTest extends TestCase
{
    use FromArrayFactory;

    #[TestDox("Should instanciate a class from an array")]
    public function testShouldBeValid()
    {
        $payload = [
            "str" => "String",
            "int" => 1983,
            "float" => 3.1415,
            "bool" => true,
            "array" => ["my", "array"],
        ];

        $instance = StubClass::fromArray($payload);

        foreach ($payload as $prop => $expected) {
            $this->assertEquals($expected, $instance->{$prop});
        }
    }
}
