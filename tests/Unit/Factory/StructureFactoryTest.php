<?php

declare(strict_types=1);

namespace Struct\Struct\Tests\Unit\Factory;

use PHPUnit\Framework\TestCase;
use Struct\Struct\Factory\StructFactory;
use Struct\Struct\Tests\Fixtures\Struct\Company;

class StructureFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        /** @var Company $company */
        $company = StructFactory::create(Company::class);
        $company->address->city = 'hello';
        self::assertInstanceOf(Company::class, $company);
    }
}
