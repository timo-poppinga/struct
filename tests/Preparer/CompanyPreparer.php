<?php

declare(strict_types=1);

namespace Struct\Struct\Tests\Preparer;

use Struct\Struct\Tests\Fixtures\Struct\Address;
use Struct\Struct\Tests\Fixtures\Struct\Company;
use Struct\Struct\Tests\Fixtures\Struct\Contact;
use Struct\Struct\Tests\Fixtures\Struct\Enum\Category;
use Struct\Struct\Tests\Fixtures\Struct\Person;
use Struct\Struct\Tests\Fixtures\Struct\Reference;
use Struct\Struct\Tests\Fixtures\Struct\Role;
use Struct\Struct\Tests\Fixtures\Struct\Technology;

class CompanyPreparer
{
    public function buildCompany(): Company
    {
        $company = new Company();
        $company->name = 'Musterfirma';
        $company->foundingDate = new \DateTime('2000-02-05 14:35:12', new \DateTimeZone('Europe/Berlin'));

        $address = new Address();
        $address->street = 'Musterstraße';
        $address->houseNumber = '99';
        $address->zip = '99999';
        $address->city = 'Musterdorf';

        $company->address = $address;

        $company->isActive = true;
        $company->category = Category::Technology;

        $company->properties = [
            'turnover' => '20m',
            'employees' => '100'
        ];

        $company->tags = [
            'industry',
            'middle size'
        ];

        $person01 = new Person();
        $person01->title = 'Geschäftsführer';
        $person01->firstName = 'Max';
        $person01->middleName = 'Maier';
        $person01->lastName = 'Mustermann';

        $person02 = new Person();
        $person02->title = 'Developer';
        $person02->firstName = 'Kai';
        $person02->lastName = 'Kaul';

        $company->persons = [
            $person01,
            $person02
        ];

        $contact = new Contact();
        $contact->type = 'phone';
        $contact->value = '+499999999';

        $person02->contacts = [
            $contact
        ];

        $role01 = new Role();
        $role02 = new Role();
        $role03 = new Role();

        $role01->name = 'blue';
        $role02->name = 'green';
        $role03->name = 'white';

        $company->roles = [
            'first' => $role01,
            'second' => $role02,
            'third' => $role03
        ];

        $company->latitude = 48.25652;
        $company->longitude = 8.0;

        $technology = new Technology();
        $technology->name = 'One CMS';
        $technology->country = 'Germany';

        $reference01 = new Reference();
        $reference01->title = 'Website Blue GmbH';
        $reference01->technologies = [
            $technology,
        ];

        $reference02 = new Reference();
        $reference02->title = 'Website Green GmbH';
        $reference02->technologies = null;

        $company->references = [
            $reference01,
            $reference02
        ];

        return $company;
    }
}
