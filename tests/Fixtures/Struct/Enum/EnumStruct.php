<?php

declare(strict_types=1);

namespace Struct\Struct\Tests\Fixtures\Struct\Enum;

use Struct\Struct\Contracts\StructInterface;

class EnumStruct implements StructInterface
{
    public Category $category = Category::Financial;
    public Type $type = Type::Hot;
    public Rating $internalRating = Rating::Middle;
    public ?Rating $externalRating = null;
}
