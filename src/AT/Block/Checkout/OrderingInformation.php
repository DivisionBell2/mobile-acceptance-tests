<?php
/**
 * @description Блок данных для оформления заказа
 */

namespace AT\Block\Checkout;

use AcceptanceCore\Core\Utils\Strings;
use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\AutoComplete;
use AT\Block\Button;
use AT\Block\Input;
use AT\Helpers\RegisterUser\UserDataGenerator;
use AT\Page\Checkout\SuccessfulOrder;

class OrderingInformation extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'ordering-information';

    private $inputCheckoutFullName;
    private $inputCheckoutPhoneNumber;
    private $inputCheckoutEmail;
    private $inputCheckoutCity;
    private $inputComment;
    private $deliveryMethodsBlock;

    private function getDeliveryMethodsBlock(): DeliveryMethods
    {
        if (is_null($this->deliveryMethodsBlock)) {
            $this->deliveryMethodsBlock = new DeliveryMethods();
        }
        return $this->deliveryMethodsBlock;
    }

    private function getFullNameInput(): Input
    {
        if (is_null($this->inputCheckoutFullName)) {
            $this->inputCheckoutFullName = new Input((string) $this->me()
                ->childByAttribute(self::TEST_ATTR_NAME, 'checkout-name'));
        }
        return $this->inputCheckoutFullName;
    }

    private function getPhoneNumberInput(): Input
    {
        if (is_null($this->inputCheckoutPhoneNumber)) {
            $this->inputCheckoutPhoneNumber = new Input((string) $this->me()
                ->childByAttribute(self::TEST_ATTR_NAME, 'checkout-phone'));
        }
        return $this->inputCheckoutPhoneNumber;
    }

    private function getEmailInput(): Input
    {
        if (is_null($this->inputCheckoutEmail)) {
            $this->inputCheckoutEmail = new Input((string) $this->me()
                ->childByAttribute(self::TEST_ATTR_NAME, 'checkout-email'));
        }
        return $this->inputCheckoutEmail;
    }

    private function getCityInput(): AutoComplete
    {
        if (is_null($this->inputCheckoutCity)) {
            $this->inputCheckoutCity = new AutoComplete((string) $this->me()
                ->childByAttribute(self::TEST_ATTR_NAME, 'suggester'));
        }
        return $this->inputCheckoutCity;
    }

    private function getCommentToOrderButton(): Button
    {
        return new Button((string) $this->me()
            ->childByAttribute(self::TEST_ATTR_NAME, 'checkout-comment-button'));
    }

    private function getCommentInput(): Input
    {
        if (is_null($this->inputComment)) {
            $this->inputComment = new Input((string) $this->me()
                ->childByAttribute(self::TEST_ATTR_NAME, 'checkout-comment-input'));
        }
        return $this->inputComment;
    }

    private function inputCity(string $cityName = 'Москва')
    {
        WB::step("Ввод {$cityName} в текстовое поле 'Город'");
        $this->getCityInput()->inputTextAndSubmitSuggest($cityName);
        Waiting::waitForAjax(30);
    }

    private function getMakeOrderButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'make-order'));
    }

    public function getFullName(): string
    {
        return $this->getFullNameInput()->getText();
    }

    public function getPhoneNumber(): string
    {
        return $this->getPhoneNumberInput()->getText();
    }

    public function getTotalPrice(): int
    {
        return (int) Strings::stripAllWhiteSpaces(WB::grabTextFrom($this->me()
            ->childByAttribute(self::TEST_ATTR_NAME, 'total-price')));
    }

    public function getOriginalSumPrice(): int
    {
        return (int) Strings::stripAllWhiteSpaces(WB::grabTextFrom($this->me()
            ->childByAttribute(self::TEST_ATTR_NAME, 'original-sum-price')));
    }

    public function getDiscountSum(): int
    {
        return (int) Strings::stripAllWhiteSpaces(WB::grabTextFrom($this->me()
            ->childByAttribute(self::TEST_ATTR_NAME, 'discount-sum-price')));
    }

    public function inputCheckoutData(
        string $checkoutFullName = 'Иванов Иван Иванович',
        string $checkoutComment = 'Тестовый заказ. Отмените пожалуйста'
    ) {
        $userDataGenerator = new UserDataGenerator();

        WB::step("Ввод $checkoutFullName в текстовое поле 'ФИО'");
        $this->getFullNameInput()->clickAndInput($checkoutFullName);

        $profileNumber = $userDataGenerator->getRandomPhoneNumber();
        WB::step("Ввод $profileNumber в текстовое поле 'Номер телефона'");
        $this->getPhoneNumberInput()->clickAndInput($profileNumber);

        $profileEmail = $userDataGenerator->getRandomEmail();
        WB::step("Ввод $profileEmail в текстовое поле 'Email'");
        $this->getEmailInput()->clickAndInput($profileEmail);

        $this->inputCity();

        WB::step("Клик по кнопке 'Самовывоз' в форме анкеты");
        $this->getDeliveryMethodsBlock()->getPickupCheckbox()->clickHTML();

        WB::step("Клик по кнопке 'Комментарий к заказу' в форме анкеты");
        $this->getCommentToOrderButton()->click();

        WB::step("Ввод {$checkoutComment} в текстовое поле 'Комментарий к заказу'");
        $this->getCommentInput()->clickAndInput($checkoutComment);
    }

    public function inputCheckoutDataByAuthUser(
        string $checkoutComment = 'Тестовый заказ. Отмените пожалуйста'
    ) {
        $this->inputCity();

        WB::step("Клик по кнопке 'Самовывоз' в форме анкеты");
        $this->getDeliveryMethodsBlock()->getPickupCheckbox()->clickHTML();

        WB::step("Клик по кнопке 'Комментарий к заказу' в форме анкеты");
        $this->getCommentToOrderButton()->click();

        WB::step("Ввод {$checkoutComment} в текстовое поле 'Комментарий к заказу'");
        $this->getCommentInput()->clickAndInput($checkoutComment);
    }

    public function clickMakeOrderButtonAndGoToSuccessfulOrderPage(): SuccessfulOrder
    {
        WB::step("Клик по кнопке 'Оформить заказ' в форме анкеты");
        $this->getMakeOrderButton()->clickHTML();
        $checkoutPage = new SuccessfulOrder();
        $checkoutPage->waitForReady();
        return $checkoutPage;
    }
}