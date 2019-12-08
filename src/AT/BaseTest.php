<?php
/**
* @description Базовый класс для всех тестов проекта
*/

namespace AT;

use AcceptanceCore\Core\Env;
use AcceptanceCore\Core\WB;
use Core\TestCase;

abstract class BaseTest extends TestCase
{
    /**
     * @description Проектная надстройка над setUp в библиотеке для того, чтобы установить необходимые куки и т.п
     * @throws \AcceptanceCore\Core\ATException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $domain = Env::getInstance()->domain();
        WB::addCookieToDomain('do_not_report_to_analytics', '1', $domain);
        WB::addCookieToDomain('butik-test', 'true', $domain);
        //Временные куки. Убрать после подключения куки, блокирующей куки с emarsys
        WB::addCookieToDomain('dy_splash-show', 'no', $domain);
        WB::addCookieToDomain('closeSmartBanner', 'true', $domain);
        WB::addCookieToDomain('closeSubscription', 'true', $domain);
        // Предотвращает отображение всех элементов, которые имеют атрибут data-wps-popup, т.е. всех оверлеев Emarsys
        WB::addCookieToDomain('hide_popups', 'true', $domain);
    }
}
