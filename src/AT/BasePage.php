<?php
/*
@description Базовый класс для всех объектов-страницы
*/

namespace AT;

use AcceptanceCore\Core\Utils\Waiting;

abstract class BasePage
{
    const TEST_ATTR_NAME = 'data-test';
    
    public function waitForReady()
    {
        Waiting::waitForLoad();
    }
}