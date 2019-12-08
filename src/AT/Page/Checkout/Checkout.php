<?php
/**
 * @description Страница корзины
 */

namespace AT\Page\Checkout;

use AcceptanceCore\Core\Utils\Waiting;
use AT\BasePage;
use AT\Block\Checkout\AuthorizationQuestionPopup;
use AT\Block\Checkout\CartContent;
use AT\Block\Checkout\CartList;
use AT\Block\Checkout\OrderingInformation;
use AT\Page\iPageTemplate;

class Checkout extends BasePage implements iPageTemplate
{
    private $orderingInformationBlock;
    private $authorizationQuestionPopup;
    private $cartList;
    private $cartContent;

    public function getOrderingInformationBlock(): OrderingInformation
    {
        if (is_null($this->orderingInformationBlock)) {
            $this->orderingInformationBlock = new OrderingInformation();
        }
        return $this->orderingInformationBlock;
    }

    public function getAuthorizationQuestionPopup(): AuthorizationQuestionPopup
    {
        if (is_null($this->authorizationQuestionPopup)) {
            $this->authorizationQuestionPopup = new AuthorizationQuestionPopup();
        }
        return $this->authorizationQuestionPopup;
    }

    public function getCartListBlock(): CartList
    {
        if (is_null($this->cartList)) {
            $this->cartList = new CartList();
        }
        return $this->cartList;
    }

    public function getCartContentBlock(): CartContent
    {
        if (is_null($this->cartContent)) {
            $this->cartContent = new CartContent();
        }
        return $this->cartContent;
    }

    public function waitForReady()
    {
        parent::waitForReady();
        Waiting::waitForElementVisible(
            $this->getOrderingInformationBlock()->me(),
            10,
            'Не удалось дождаться видимости блока карточек товара в корзине'
        );
    }
}