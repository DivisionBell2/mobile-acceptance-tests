<?php
/**
* @description Тест на проверку наличия ключевой информации на странице "Об Универмаге"
*/

namespace AT\Test\Mobile\About;

use AT\BaseTest;
use AT\Helpers\Main\FooterNavigationMenuNames;
use AT\Helpers\About\Openers\AboutPageOpener;

class CheckMainElementsOnAboutPage extends BaseTest
{
    public function testContentOnPage()
    {
        $aboutShopPage = (new AboutPageOpener())->openAboutPage();

        $this->assertTrue(
            $aboutShopPage->getMobileFooterNavigationMenuSelect()->isVisible(),
            "Блок выпадающего меню навигации не виден на странице"
        );

        $this->assertTrue(
            $aboutShopPage->isBaseContentVisible(),
            'Базовый контент не отображается на странице ' . FooterNavigationMenuNames::ABOUT_SHOP
        );
    }
}
