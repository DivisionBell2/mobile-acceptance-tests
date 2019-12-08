<?php
/**
* @description Открыватель главной страницы 'Об Универмаге'
*/

namespace AT\Helpers\About\Openers;

use AcceptanceCore\Core\WB;
use AT\Page\About\About;

class AboutPageOpener
{
    public function openAboutPage(): About
    {
        WB::open('/about');
        $page = new About();
        $page->waitForReady();
        WB::step('Открылась страница: Об Универмаге');
        return $page;
    }
}