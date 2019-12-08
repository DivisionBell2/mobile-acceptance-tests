<?php
/**
 * @description Блок хедера в попапе авторизации
 */

namespace AT\Block\AuthorizationAndRegistration;

use AcceptanceCore\Core\ATException;
use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\Button;

class PopupHeader extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'login-popup-header';

    private function getRegistrationBookmark(): Button
    {
        return new Button((string) $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'registration-bookmark-button'));
    }

    public function clickOnRegistrationButtonAndGetToRegistrationForm(): RegistrationForm
    {
        WB::step("Клик по кнопке 'Регистрация' в хедере окна авторизации и переход к форме регистрации");
        $this->getRegistrationBookmark()->click();
        $registrationForm = new RegistrationForm();
        if (Waiting::waitForElementVisible($registrationForm, 3, 'Форма регистрации не видна на странице') && $this->isRegistrationBookmarkSelected()) {
            return $registrationForm;
        }
        throw new ATException("Блок 'Регистрация' не активен");
    }

    public function isRegistrationBookmarkSelected(): bool
    {
        WB::step("Проверка отображения вкладки 'Регистрация', как активной");
        return WB::getAttribute($this->getRegistrationBookmark()->me(), "aria-current") == "true";
    }
}