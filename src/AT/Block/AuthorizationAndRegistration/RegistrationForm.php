<?php
/**
 * @description Блок формы регистрации пользователя
 */

namespace AT\Block\AuthorizationAndRegistration;

use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\Button;
use AT\Block\Input;
use AT\Helpers\RegisterUser\UserDataGenerator;

class RegistrationForm extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'registration-form';

    private $inputName;
    private $inputEmail;
    private $inputPhone;
    private $inputPassword;
    private $welcomeWindow;

    private function getUserNameInput(): Input
    {
        if (is_null($this->inputName)) {
            $this->inputName = new Input((string) $this->me()->childByAttribute('name', 'name_entered'));
        }
        return $this->inputName;
    }

    private function getUserEmailInput(): Input
    {
        if (is_null($this->inputEmail)) {
            $this->inputEmail = new Input((string) $this->me()->childByAttribute('name', 'email'));
        }
        return $this->inputEmail;
    }

    private function getUserPhoneInput(): Input
    {
        if (is_null($this->inputPhone)) {
            $this->inputPhone = new Input((string) $this->me()->childByAttribute('name', 'phone'));
        }
        return $this->inputPhone;
    }

    private function getUserPasswordInput(): Input
    {
        if (is_null($this->inputPassword)) {
            $this->inputPassword = new Input((string) $this->me()->childByAttribute('type', 'password'));
        }
        return $this->inputPassword;
    }

    private function getRegistrationButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'registration-button'));
    }

    public function getWelcomeWindow(): WelcomeWindow
    {
        if (is_null($this->welcomeWindow)) {
            $this->welcomeWindow = new WelcomeWindow();
        }
        return $this->welcomeWindow;
    }

    public function registerNewUser(string $name = 'Ivanov Ivan', ?string $email = null, ?string $phone = null, ?string $password = null)
    {
        $userDataGenerator = new UserDataGenerator();
        if (is_null($email)) {
            $email = $userDataGenerator->getRandomEmail();
        }
        if (is_null($phone)) {
            $phone = $userDataGenerator->getRandomPhoneNumber();
        }
        if (is_null($password)) {
            $password = $userDataGenerator::PASSWORD;
        }

        WB::step("Ввод {$name} в текстовое поле 'Имя'");
        $this->getUserNameInput()->input($name);
        WB::step("Ввод {$email} в текстовое поле 'E-mail'");
        $this->getUserEmailInput()->input($email);
        WB::step("Ввод {$phone} в текстовое поле 'Телефон'");
        $this->getUserPhoneInput()->input($phone);
        WB::step("Ввод {$password} в текстовое поле 'Пароль'");
        $this->getUserPasswordInput()->input($password);
        WB::step("Клик по кнопке 'Войти' в форме регистрации");
        $this->getRegistrationButton()->click();
        Waiting::waitForElementVisible($this->getWelcomeWindow(), 10, "Не появилось окно приветствия");
        Waiting::waitForElementNotVisible($this->getWelcomeWindow(), 10, "Не исчезло окно приветствия");
    }
}