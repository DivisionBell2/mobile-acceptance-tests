<?php
/**
* @description Открыватель главной страницы проекта Butik
*/

namespace AT\Helpers\Main\Openers;

use AcceptanceCore\Core\WB;
use AT\Page\Main\Main;

class MainPageOpener
{
    public function openMainPage(): Main
    {
        WB::open('/');
        $page = new Main();
        $page->waitForReady();
        WB::step('Открылась страница: Главная проекта Butik');
        return $page;
    }
}