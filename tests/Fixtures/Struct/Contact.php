<?php

declare(strict_types=1);

namespace Struct\Struct\Tests\Fixtures\Struct;

use Struct\Struct\Contracts\StructInterface;

class Contact implements StructInterface
{
    public string $type = '';
    public string $value = '';
}
