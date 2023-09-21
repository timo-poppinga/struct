<?php

declare(strict_types=1);

namespace Struct\Struct\Tests\Fixtures\Struct;

use Struct\Struct\Contracts\Attribute\ArrayKeyList;
use Struct\Struct\Contracts\Attribute\ArrayList;
use Struct\Struct\Contracts\Attribute\DefaultValue;
use Struct\Struct\Contracts\StructInterface;
use Struct\Struct\Tests\Fixtures\Struct\Enum\Category;

class Company implements StructInterface
{
    public string $name = '';

    #[DefaultValue('2022-05-05 00:00:00')]
    public \DateTimeInterface $foundingDate;
    public Address $address;
    public bool $isActive;
    public Category $category;
    public Category $category2 = Category::Financial;

    /**
     * @var array<string, string>
     */
    #[ArrayKeyList('string')]
    public array $properties = [];

    /**
     * @var array<string>
     */
    #[ArrayList('string')]
    public array $tags = [];

    /**
     * @var Person[]
     */
    #[ArrayList(Person::class)]
    public array $persons = [];

    public int $age = 20;

    /**
     * @var Role[]
     */
    #[ArrayKeyList(Role::class)]
    public array $roles = [];

    public float $longitude;
    public float $latitude;

    /**
     * @var Reference[]
     */
    #[ArrayKeyList(Reference::class)]
    public array $references = [];
}
