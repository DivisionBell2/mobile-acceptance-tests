<?php
/**
* @description Тест на проверку успешной авторизации зарегистрированным пользователем
*/

namespace AT\Test\Mobile\Registration;

use AT\BaseTest;
use AT\Helpers\Main\Openers\MainPageOpener;
use AT\Helpers\RegisterUser\UserDataGenerator;

class CheckSuccessfulRegistration extends BaseTest
{
    public function testCheckSuccefulRegistration()
    {
        $expectedUserName = 'Ivanov Ivan';

        $mainPage = (new MainPageOpener())->openMainPage();
        $mainPage->openAndGetMainMenuBlock();
        $authPopup = $mainPage->getMainMenu()->getUserMenu()->clickUserAuthButtonAndGetAuthPopupBlock();
        $authPopup->getPopupHeader()->clickOnRegistrationButtonAndGetToRegistrationForm();

        $userDataGenerator = new UserDataGenerator();
        $userEmail = $userDataGenerator->getRandomEmail();
        $userPhone = $userDataGenerator->getRandomPhoneNumber();
        $authPopup->getRegistrationForm()->registerNewUser($expectedUserName, $userEmail, $userPhone, $userDataGenerator::PASSWORD);

        $this->assertPattern(
            $expectedUserName,
            $mainPage->openAndGetMainMenuBlock()->getUserMenu()->getLoggedInUserName(),
            'Имя пользователя в главном меню не соответствует ожидаемому значению ' . $expectedUserName
        );
    }
}