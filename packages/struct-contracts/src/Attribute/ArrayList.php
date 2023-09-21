<?php

declare(strict_types=1);

namespace Struct\Struct\Contracts\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ArrayList
{
    // @phpstan-ignore-next-line
    public function __construct(string $type)
    {
    }
}
