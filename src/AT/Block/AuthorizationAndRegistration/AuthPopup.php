<?php
/**
 * @description Основное окно авторизации и регистрации пользователя
 */

namespace AT\Block\AuthorizationAndRegistration;

use AcceptanceCore\Core\Utils\Waiting;
use AT\BaseBlock;

class AuthPopup extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'auth-popup';

    private $popupHeader;
    private $authorizationForm;
    private $registrationForm;

    public function getPopupHeader(): PopupHeader
    {
        if (is_null($this->popupHeader)) {
            $this->popupHeader = new PopupHeader();
        }
        return $this->popupHeader;
    }

    public function getAuthorizationForm(): AuthForm
    {
        if (is_null($this->authorizationForm)) {
            $this->authorizationForm = new AuthForm();
        }
        return $this->authorizationForm;
    }

    public function getRegistrationForm(): RegistrationForm
    {
        if (is_null($this->registrationForm)) {
            $this->registrationForm = new RegistrationForm();
        }
        return $this->registrationForm;
    }

    public function waitForReady()
    {
        Waiting::waitForLoad();
        Waiting::waitForElementVisible(
            $this->getPopupHeader()->me(),
            3,
            'Не удалось дождаться видимости хедера окна авторизации'
        );
    }
}