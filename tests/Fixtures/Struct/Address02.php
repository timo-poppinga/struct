<?php

declare(strict_types=1);

namespace Struct\Struct\Tests\Fixtures\Struct;

use Struct\Struct\Contracts\StructInterface;

class Address02 implements StructInterface
{
    public string $street = '';
    public string $zip = '';
    public string $city = '';
}
