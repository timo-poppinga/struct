<?php

declare(strict_types=1);

namespace Struct\Struct\Tests\Unit\Utility;

use PHPUnit\Framework\TestCase;
use Struct\Struct\Contracts\StructInterface;
use Struct\Struct\Exception\InvalidValueException;
use Struct\Struct\Tests\Fixtures\Struct\Company;
use Struct\Struct\Tests\Fixtures\Struct\Wrong;
use Struct\Struct\Tests\Preparer\CompanyPreparer;
use Struct\Struct\Tests\Proxy\Utility\StructSerializeUtilityProxy;

class StructSerializeUtilityTest extends TestCase
{
    protected StructSerializeUtilityProxy $subject;
    protected Company $company;
    protected string $expectation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new StructSerializeUtilityProxy();
        $companyPreparer = new CompanyPreparer();
        $this->company = $companyPreparer->buildCompany();
        $this->expectation = (string) \file_get_contents(__DIR__ . '/../../Expectation/Company.json');
        $this->expectation = \substr($this->expectation, 0, -1);
    }

    public function testFullSerialize(): void
    {
        $companyJson = $this->subject->serializeJson($this->company);
        self::assertSame($this->expectation, $companyJson);
    }

    public function testFullUnSerialize(): void
    {
        $companyArrayExpectation = $this->subject->serialize($this->company);
        /** @var Company $companyUnSerialize */
        $companyUnSerialize = $this->subject->unSerialize($companyArrayExpectation, Company::class);
        self::assertSame($this->company->name, $companyUnSerialize->name);
    }

    public function testInvalidValueException(): void
    {
        $wrong = new Wrong();
        $this->expectException(InvalidValueException::class);
        $this->subject->serialize($wrong);
    }

    public function testUnSerializeBadType(): StructInterface
    {
        $this->expectException(InvalidValueException::class);
        return $this->subject->unSerializeJson($this->expectation, 'ImNotAnStructure');  // @phpstan-ignore-line
    }

    public function testUnSerialize(): void
    {
        $company = $this->subject->unSerializeJson($this->expectation, Company::class);
        self::assertInstanceOf(Company::class, $company);
    }

    public function testUnSerializeObject(): void
    {
        $company = $this->subject->unSerialize($this->company, Company::class);
        self::assertInstanceOf(Company::class, $company);
    }
}
