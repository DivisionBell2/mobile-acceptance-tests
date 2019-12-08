<?php
/**
* @description Тест на проверку успешной авторизации зарегистрированным пользователем
*/

namespace AT\Test\Mobile\Authorization;

use AT\BaseTest;
use AT\Helpers\Main\Openers\MainPageOpener;

class CheckSuccessfulLogin extends BaseTest
{
    public function testCheckSuccefullLogin()
    {
        $expectedUserNameEntered = 'Test User';

        $mainPage = (new MainPageOpener())->openMainPage();
        $mainPage->openAndGetMainMenuBlock();
        $authPopup = $mainPage->getMainMenu()->getUserMenu()->clickUserAuthButtonAndGetAuthPopupBlock();
        $authPopup->getAuthorizationForm()->login($expectedUserNameEntered);
        $mainPage->openAndGetMainMenuBlock();

        $this->assertPattern(
            $expectedUserNameEntered,
            $mainPage->getMainMenu()->getUserMenu()->getLoggedInUserName(),
            'Имя пользователя соответствует ожидаемому значению ' . $expectedUserNameEntered
        );
    }
}
