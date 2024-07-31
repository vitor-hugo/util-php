<?php declare(strict_types=1);

namespace Tests\Traits;

use PHPUnit\Framework\Attributes\Depends;
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

    protected string $x;
    private mixed $y;


    public function __construct(readonly string $z = "z")
    {
    }
}

#[Group("Traits")]
#[Group("FromArrayFactory")]
#[TestDox("FromArrayFactory")]
class FromArrayFactoryTest extends TestCase
{
    #[TestDox("Should instantiate a class from an array")]
    public function testShouldInstantiateFromArray(): array
    {
        $payload = [
            "str" => "String",
            "int" => 1983,
            "float" => 3.1415,
            "bool" => true,
            "array" => ["my", "array"],
            "mixed" => [0, 1.1, "2", false],
            "name" => "Full Name"
        ];

        $instance = StubClass::fromArray($payload);

        foreach ($payload as $prop => $expected) {
            $this->assertEquals($expected, $instance->{$prop});
        }

        return [$payload, $instance];
    }


    #[Depends("testShouldInstantiateFromArray")]
    #[TestDox("Should return all public properties as Key=>Value pair array")]
    public function testShouldReturnPropertiesAsArray(array $data)
    {
        [$payload, $instance] = $data;
        $payload["z"] = "z";
        $this->assertEquals($payload, $instance->toArray());
    }
}
