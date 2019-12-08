<?php
/**
* @description Блок меню пользователя в главном меню
*/

namespace AT\Block\HeaderMenu;

use AT\BaseBlock;
use AT\Block\AuthorizationAndRegistration\AuthPopup;
use AT\Page\Main\Main;
use AT\Block\AuthorizationAndRegistration\AuthForm;
use AT\Block\Button;
use AcceptanceCore\Core\WB;
use AcceptanceCore\Core\Selector;

class UserMenuBlock extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'mobile-user-menu-list';

    private $authForm;

    public function getAuthForm(): AuthForm
    {
        if (is_null($this->authForm)) {
            $this->authForm = new AuthForm();
        }
        return $this->authForm;
    }

    private function getUserMenuNameSelector(): Selector
    {
        return $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'mobile-user-menu-name');
    }

    private function getUserMenuLogoutButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'mobile-user-menu-logout'));
    }

    private function getUserAuthButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute('data-bind', 'mobile-user-menu'));
    }

    public function getLoggedInUserName(): string
    {
        return WB::grabTextFrom($this->getUserMenuNameSelector());
    }

    public function clickUserLogoutButtonAndReturnOnMainPage(): Main
    {
        WB::step("Клик по кнопке 'Выйти' в меню пользователя");
        $this->getUserMenuLogoutButton()->click();
        $mainPage = new Main();
        $mainPage->waitForReady();
        return $mainPage;
    }

    public function clickUserAuthButtonAndGetAuthFormBlock(): AuthForm
    {
        WB::step("Клик по кнопке 'Войти' в главном меню");
        $this->getUserAuthButton()->click();
        return $this->getAuthForm();
    }

    public function clickUserAuthButtonAndGetAuthPopupBlock(): AuthPopup
    {
        WB::step("Клик по кнопке 'Войти' в главном меню");
        $this->getUserAuthButton()->click();
        $authPopup = new AuthPopup();
        $authPopup->waitForReady();
        return $authPopup;
    }

    public function isUserMenuNameNotVisible(): bool
    {
        WB::step("Проверка на невидимость элемента с именем пользователя в меню");
        return WB::isElementNotVisible($this->getUserMenuNameSelector());
    }

    public function isUserAuthButtonVisible(): bool
    {
        WB::step("Проверка на видимость кнопки 'Войти'");
        return WB::isElementVisible($this->getUserAuthButton());
    }
}