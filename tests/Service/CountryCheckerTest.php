<?php

declare(strict_types=1);

namespace Vlad\Commissions\tests\Service;

use PHPUnit\Framework\TestCase;
use Vlad\Commissions\Service\CountryChecker;

class CountryCheckerTest extends TestCase
{
    public function testIsEu(): void
    {
        $euCountryCodes = [
            'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES',
            'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU',
            'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'
        ];

        $nonEuCountryCode = 'US';

        foreach ($euCountryCodes as $countryCode) {
            $this->assertTrue(CountryChecker::isEu($countryCode));
        }

        $this->assertFalse(CountryChecker::isEu($nonEuCountryCode));
    }
}
