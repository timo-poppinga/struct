<?php

declare(strict_types=1);

namespace Struct\Struct\Tests\Fixtures\Struct;

use Struct\Struct\Contracts\Attribute\ArrayKeyList;
use Struct\Struct\Contracts\StructInterface;

class Person implements StructInterface
{
    public string $title = '';
    public string $firstName = '';
    public ?string $middleName = null;
    public string $lastName = '';

    /**
     * @var Contact[]
     */
    #[ArrayKeyList(Contact::class)]
    public array $contacts = [];
}
