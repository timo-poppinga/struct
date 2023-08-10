<?php

declare(strict_types=1);

namespace Struct\Struct\Tests\Fixtures\Struct;

use Struct\Struct\Contracts\Attribute\ArrayList;
use Struct\Struct\Contracts\StructInterface;

class Reference implements StructInterface
{
    public string $title;
    /**
     * @var Technology[]|null
     */
    #[ArrayList(Technology::class)]
    public ?array $technologies = null;
}
