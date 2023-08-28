<?php

declare(strict_types = 1);

namespace Vlad\Commissions\Service;

class CountryChecker
{
    public static function isEu($countryCode): bool
    {
        $euCountries = [
            'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES',
            'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU',
            'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'
        ];

        return in_array($countryCode, $euCountries, true);
    }
}
