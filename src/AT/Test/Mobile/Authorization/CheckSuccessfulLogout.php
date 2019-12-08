<?php
/**
* @description Тест на проверку успешного разлогина пользователя
*/

namespace AT\Test\Mobile\Authorization;

use AT\BaseTest;
use AT\Helpers\Main\Openers\MainPageOpener;
use AT\Helpers\RegisterUser\RegisteredUser;

class CheckSuccessfulLogout extends BaseTest
{
    public function testCheckSuccefullLogout()
    {
        $mainPage = (new MainPageOpener())->openMainPage();
        $mainPage->openAndGetMainMenuBlock();
        $authPopup = $mainPage->getMainMenu()->getUserMenu()->clickUserAuthButtonAndGetAuthPopupBlock();
        $authPopup->getAuthorizationForm()->login();
        $mainPage->openAndGetMainMenuBlock();
        $mainPage->getMainMenu()->getUserMenu()->clickUserLogoutButtonAndReturnOnMainPage();
        $mainPage->openAndGetMainMenuBlock();

        $this->assertTrue(
            $mainPage->getMainMenu()->getUserMenu()->isUserMenuNameNotVisible(),
            'Элемент с именем пользователя отсутствует в меню'
        );
        
        $this->assertTrue(
            $mainPage->getMainMenu()->getUserMenu()->isUserAuthButtonVisible(),
            'Кнопка "Войти" присутствует в меню'
        );
    }
}
