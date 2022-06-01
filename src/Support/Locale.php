<?php

declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient\Support;

use PolarisDC\ExactOnline\BaseClient\Exceptions\ExactOnlineClientException;

final class Locale
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

    public static array $locales = [
        self::AMERICAN_ENGLISH => ['label' => 'American (English)', 'locale' => 'en-US'],
        self::ARABIC => ['label' => 'Arabic', 'locale' => 'ar-SA'],
        self::ARABIC_SOUTHERN_REGION => ['label' => 'Arabic (Southern region)', 'locale' => 'ar-AE'],
        self::AUSTRALIAN_ENGLISH => ['label' => 'Australian (English)', 'locale' => 'en-AU'],
        self::BULGARIAN => ['label' => 'Bulgarian', 'locale' => 'bg-BG'],
        self::CATALAN => ['label' => 'Catalan', 'locale' => 'ca-ES'],
        self::CROATIAN => ['label' => 'Croatian', 'locale' => 'hr-HR'],
        self::CZECH => ['label' => 'Czech', 'locale' => 'cs-CZ'],
        self::DANISH => ['label' => 'Danish', 'locale' => 'da-DK'],
        self::DUTCH => ['label' => 'Dutch', 'locale' => 'nl-NL'],
        self::DUTCH_BE => ['label' => 'Dutch (Belgium)', 'locale' => 'nl-BE'],
        self::ENGLISH => ['label' => 'English', 'locale' => 'en-GB'],
        self::FINNISH => ['label' => 'Finnish', 'locale' => 'fi-FI'],
        self::FRENCH => ['label' => 'French', 'locale' => 'fr-FR'],
        self::FRENCH_BE => ['label' => 'French (Belgium)', 'locale' => 'fr-BE'],
        self::GERMAN => ['label' => 'German', 'locale' => 'de-DE'],
        self::GREEK => ['label' => 'Greek', 'locale' => 'el-GR'],
        self::HUNGARIAN => ['label' => 'Hungarian', 'locale' => 'hu-HU'],
        self::ITALIAN => ['label' => 'Italian', 'locale' => 'it-IT'],
        self::JAPANESE => ['label' => 'Japanese', 'locale' => 'ja-JP'],
        self::KOREAN => ['label' => 'Korean', 'locale' => 'ko-KR'],
        self::NORWEGIAN => ['label' => 'Norwegian', 'locale' => 'nb-NO'],
        self::POLISH => ['label' => 'Polish', 'locale' => 'pl-PL'],
        self::PORTUGUESE => ['label' => 'Portuguese', 'locale' => 'pt-PT'],
        self::ROMANIAN => ['label' => 'Romanian', 'locale' => 'ro-RO'],
        self::RUSSIAN => ['label' => 'Russian', 'locale' => 'ru-RU'],
        self::SIMPLIFIED_CHINESE => ['label' => 'Simplified Chinese', 'locale' => 'zh-CH'],
        self::SLOVAK => ['label' => 'Slovak', 'locale' => 'sk-SK'],
        self::SPANISH => ['label' => 'Spanish', 'locale' => 'es-ES'],
        self::SPANISH_COLOMBIA => ['label' => 'Spanish (Colombia)', 'locale' => 'es-CO'],
        self::SWEDISH => ['label' => 'Swedish', 'locale' => 'sv-SE'],
        self::THAI => ['label' => 'Thai', 'locale' => 'th-TH'],
        self::TRADITIONAL_CHINESE => ['label' => 'Traditional Chinese', 'locale' => 'zh-CN'],
        self::TURKISH => ['label' => 'Turkish', 'locale' => 'tr-TR'],
        self::VIETNAMESE => ['label' => 'Vietnamese', 'locale' => 'vi-VN'],
    ];

    public static function convertIso6391toExactLocale(string $iso6391): string
    {
        $locale = array_filter(self::$locales, static fn ($value) => $value['locale'] === $iso6391);

        return $locale
            ? array_key_first($locale) // return from mapping
            : strtoupper($iso6391); // return the entered locale
    }

    public static function convertExactLocaleToIso6391(string $exactOnlineLocale): string
    {
        if (isset(self::$locales[$exactOnlineLocale]['locale'])) {
            return self::$locales[$exactOnlineLocale]['locale'];
        }

        throw new ExactOnlineClientException(sprintf('Unable to map Exact Online locale: "%s"', $exactOnlineLocale), 400);
    }
}
