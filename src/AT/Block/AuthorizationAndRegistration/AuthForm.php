<?php
/**
* @description Блок формы авторизации
*/

namespace AT\Block\AuthorizationAndRegistration;

use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Helpers\RegisterUser\RegisteredUser;
use AcceptanceCore\Core\Utils\Waiting;
use AT\Block\Input;
use AT\Block\Button;

class AuthForm extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'authorisation-form';
    
    private $inputEmail;
    private $inputPassword;
    private $authButton;

    private function getUserEmailInput(): Input
    {
        if (is_null($this->inputEmail)) {
            $this->inputEmail = new Input((string) $this->me()->childByAttribute('name', 'email'));
        }
        return $this->inputEmail;
    }

    private function getUserPasswordInput(): Input
    {
        if (is_null($this->inputPassword)) {
            $this->inputPassword = new Input((string) $this->me()->childByAttribute('name', 'password'));
        }
        return $this->inputPassword;
    }

    private function getAuthButton(): Button
    {
        if (is_null($this->authButton)) {
            $this->authButton = new Button((string) $this->me()->childByAttribute('type', 'submit'));
        }
        return $this->authButton;
    }

    public function login(string $userName = 'Ivan Ivanov', ?string $email = null, ?string $password = null)
    {
        if (is_null($email) && is_null($password)) {
            $createdUser = new RegisteredUser($userName);
            $email = $createdUser->getCreatedUserEmail();
            $password = $createdUser->getCreatedUserPassword();
        }
        WB::step("Ввод $email в текстовое поле 'Email или телефон'");
        $this->getUserEmailInput()->input($email);
        WB::step("Ввод $password в текстовое поле 'Пароль'");
        $this->getUserPasswordInput()->clickAndInput($password);
        WB::step("Клик по кнопке 'Войти' в форме авторизации");   
        $this->getAuthButton()->click();
        Waiting::waitForElementNotVisible($this->getAuthButton()->me(), null, "Не удалось дождаться невидимости кнопки авторизации");
    }
}