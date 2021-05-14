<?php

declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient\Support;

final class ExactLocale
{
    const DUTCH_BELGIUM = 'NL-BE';
    const FRENCH_BELGIUM = 'FR-BE';
    const ITALIAN = 'IT';
    const ENGLISH = 'EN';
    const GERMAN = 'DE';
    const PORTUGUESE = 'PT';
    const SPANISH = 'ES';

    public static function getExactLocale(string $isoLocale): string
    {
        switch ($isoLocale) {
            case self::ENGLISH:
            case 'en-GB':
            case 'en-US':
                return self::ENGLISH;

            case self::ITALIAN:
            case 'it-IT':
                return self::ITALIAN;

            case self::GERMAN:
            case 'de-DE':
                return self::GERMAN;

            case self::FRENCH_BELGIUM:
            case 'fr-FR':
            case 'fr-BE':
                return self::FRENCH_BELGIUM;

            case self::DUTCH_BELGIUM:
            case 'nl-BE':
            case 'nl-NL':
            default:
                return self::DUTCH_BELGIUM;
        }
    }
}
