<?php
/**
 * @description Утилиты для работы c продуктовыми доменами
 */

namespace Core\Utils;

use AcceptanceCore\Core\Env;
use AcceptanceCore\Core\Utils\Strings;

class Domains
{
    const DEVEL = 'devel';
    const STAGE = 'stage';
    const PRODUCTION = 'production';

    public static function isProductionDomain(): bool
    {
        $domain = Env::getInstance()->domain();
        return Strings::isStringFoundIn('butik.ru', $domain) && !self::isStageDomain() && !self::isDevelDomain();
    }

    public static function isStageDomain(): bool
    {
        $domain = Env::getInstance()->domain();
        return Strings::isStringFoundIn('stage.butik.ru', $domain);
    }

    public static function isDevelDomain(): bool
    {
        $domain = Env::getInstance()->domain();
        return preg_match('/dev(\d+).butik.ru/m', $domain) === 1;
    }
}
