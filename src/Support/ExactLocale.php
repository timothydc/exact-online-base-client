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

    public static array $localeTable = [
        self::AMERICAN_ENGLISH => 'American (English)',
        self::ARABIC => 'Arabic',
        self::ARABIC_SOUTHERN_REGION => 'Arabic (Southern region)',
        self::AUSTRALIAN_ENGLISH => 'Australian (English)',
        self::BULGARIAN => 'Bulgarian',
        self::CATALAN => 'Catalan',
        self::CROATIAN => 'Croatian',
        self::CZECH => 'Czech',
        self::DANISH => 'Danish',
        self::DUTCH => 'Dutch',
        self::DUTCH_BE => 'Dutch (Belgium)',
        self::ENGLISH => 'English',
        self::FINNISH => 'Finnish',
        self::FRENCH => 'French',
        self::FRENCH_BE => 'French (Belgium)',
        self::GERMAN => 'German',
        self::GREEK => 'Greek',
        self::HUNGARIAN => 'Hungarian',
        self::ITALIAN => 'Italian',
        self::JAPANESE => 'Japanese',
        self::KOREAN => 'Korean',
        self::NORWEGIAN => 'Norwegian',
        self::POLISH => 'Polish',
        self::PORTUGUESE => 'Portuguese',
        self::ROMANIAN => 'Romanian',
        self::RUSSIAN => 'Russian',
        self::SIMPLIFIED_CHINESE => 'Simplified Chinese',
        self::SLOVAK => 'Slovak',
        self::SPANISH => 'Spanish',
        self::SPANISH_COLOMBIA => 'Spanish (Colombia)',
        self::SWEDISH => 'Swedish',
        self::THAI => 'Thai',
        self::TRADITIONAL_CHINESE => 'Traditional Chinese',
        self::TURKISH => 'Turkish',
        self::VIETNAMESE => 'Vietnamese',
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

        if (array_key_exists($locale, self::$localeTable)) {
            return $locale;
        }

        throw new ExactOnlineClientException(sprintf('Unable to use unknown locale: "%s"', $isoLocale), 500);
    }
}
