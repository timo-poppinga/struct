<?php

declare(strict_types=1);

namespace Struct\Struct\Tests\Fixtures\Struct;

use Struct\Struct\Contracts\StructInterface;

class Company02 implements StructInterface
{
    public string $name;
    public \DateTimeInterface $foundingDate;
    public Address02 $address;
    public bool $isActive;
    public float $longitude;
    public float $latitude;
}
