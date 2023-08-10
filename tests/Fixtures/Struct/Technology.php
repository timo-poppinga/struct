<?php

declare(strict_types=1);

namespace Struct\Struct\Tests\Fixtures\Struct;

use Struct\Struct\Contracts\StructInterface;

class Technology implements StructInterface
{
    public string $name;
    public ?string $country = null;
}
