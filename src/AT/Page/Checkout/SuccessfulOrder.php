<?php
/**
 * @description Страница с информацией об успешном заказе
 */

namespace AT\Page\Checkout;

use AcceptanceCore\Core\Utils\Waiting;
use AT\BasePage;
use AT\Block\Checkout\SuccessfulOrderInfo;
use AT\Page\iPageTemplate;

class SuccessfulOrder extends BasePage implements iPageTemplate
{
    private $orderInfo;

    public function getSuccessfulOrderInfoBlock(): SuccessfulOrderInfo
    {
        if (is_null($this->orderInfo)) {
            $this->orderInfo = new SuccessfulOrderInfo();
        }
        return $this->orderInfo;
    }

    public function waitForReady()
    {
        parent::waitForReady();
        Waiting::waitForAjax(5);
        Waiting::waitForElementVisible(
            $this->getSuccessfulOrderInfoBlock()->me(),
            35,
            'Не удалось дождаться видимости блока информации оформленного заказа'
        );
    }
}