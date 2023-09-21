<?php

declare(strict_types=1);

namespace Struct\Struct\Tests\Fixtures\Struct\Enum;

enum Category: string
{
    case Technology = 'cat-technology';
    case Healthcare = 'cat-healthcare';
    case Financial = 'cat-financial';
    case Industry = 'cat-industry';
}
