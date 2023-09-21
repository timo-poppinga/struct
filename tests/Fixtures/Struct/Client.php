<?php

declare(strict_types=1);

namespace Struct\Struct\Tests\Fixtures\Struct;

use Struct\Struct\Contracts\StructInterface;

class Client implements StructInterface
{
    public string $name;
    public Address $invoiceAddress;
    public ?Address $deliveryAddress;
}
