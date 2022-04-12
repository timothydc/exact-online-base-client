<?php

declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient\Support;

use PolarisDC\ExactOnline\BaseClient\Exceptions\ExactOnlineClientException;

final class ExactLocale
{
    public const AMERICAN_ENGLISH = 'EN-US';
    public const ARABIC = 'AR';
    public const ARABIC_SOUTHERN_REGION = 'AR-AE';
    public const AUSTRALIAN_ENGLISH = 'EN-AU';
    public const BULGARIAN = 'BG';
    public const CATALAN = 'CA-ES';
    public const CROATIAN = 'HR';
    public const CZECH = 'CS';
    public const DANISH = 'DA';
    public const DUTCH = 'NL';
    public const DUTCH_BE = 'NL-BE';
    public const ENGLISH = 'EN';
    public const FINNISH = 'FI';
    public const FRENCH = 'FR';
    public const FRENCH_BE = 'FR-BE';
    public const GERMAN = 'DE';
    public const GREEK = 'EL';
    public const HUNGARIAN = 'HU';
    public const ITALIAN = 'IT';
    public const JAPANESE = 'JA';
    public const KOREAN = 'KO';
    public const NORWEGIAN = 'NB-NO';
    public const POLISH = 'PL';
    public const PORTUGUESE = 'PT';
    public const ROMANIAN = 'RO';
    public const RUSSIAN = 'RU';
    public const SIMPLIFIED_CHINESE = 'ZH-CHS';
    public const SLOVAK = 'SK';
    public const SPANISH = 'ES';
    public const SPANISH_COLOMBIA = 'ES-CO';
    public const SWEDISH = 'SV';
    public const THAI = 'TH';
    public const TRADITIONAL_CHINESE = 'ZH-CHT';
    public const TURKISH = 'TR';
    public const VIETNAMESE = 'VI';

    public static array $activeLocales = [
        self::AMERICAN_ENGLISH,
        self::ARABIC,
        self::ARABIC_SOUTHERN_REGION,
        self::AUSTRALIAN_ENGLISH,
        self::BULGARIAN,
        self::CATALAN,
        self::CROATIAN,
        self::CZECH,
        self::DANISH,
        self::DUTCH,
        self::DUTCH_BE,
        self::ENGLISH,
        self::FINNISH,
        self::FRENCH,
        self::FRENCH_BE,
        self::GERMAN,
        self::GREEK,
        self::HUNGARIAN,
        self::ITALIAN,
        self::JAPANESE,
        self::KOREAN,
        self::NORWEGIAN,
        self::POLISH,
        self::PORTUGUESE,
        self::ROMANIAN,
        self::RUSSIAN,
        self::SIMPLIFIED_CHINESE,
        self::SLOVAK,
        self::SPANISH,
        self::SPANISH_COLOMBIA,
        self::SWEDISH,
        self::THAI,
        self::TRADITIONAL_CHINESE,
        self::TURKISH,
        self::VIETNAMESE,
    ];

    /**
     * @throws ExactOnlineClientException
     */
    public static function getLocale(string $isoLocale): string
    {
        $locale = match ($isoLocale) {
            'en-GB' => self::ENGLISH,
            'it-IT' => self::ITALIAN,
            'de-DE' => self::GERMAN,
            'fr-FR' => self::FRENCH,
            'nl-NL' => self::DUTCH,
            default => \strtoupper($isoLocale),
        };

        if (\in_array($locale, self::$activeLocales, true)) {
            return $locale;
        }

        throw new ExactOnlineClientException(sprintf('Unable to use unknown locale: "%s"', $isoLocale), 500);
    }
}
