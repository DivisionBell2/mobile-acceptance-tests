<?php
/**
 * @description Блок контента корзины вне информации о товаре
 */

namespace AT\Block\Checkout;

use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\Button;
use AT\Page\Main\Main;

class CartContent extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'cart-content';

    private function getGoToSalesButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'go-to-sales-button'));
    }

    public function clickOnToSalesButton(): Main
    {
        $goToSalesButton = $this->getGoToSalesButton();
        Waiting::waitForElementVisible(
            $goToSalesButton->me(),
            3,
            "Кнопка 'Перейкти к покупкам' отсутствует на экране"
        );
        WB::step("Клик по кнопке 'Перейти к покупкам' и переход в каталог");
        $goToSalesButton->click();
        $mainPage = new Main();
        $mainPage->waitForReady();
        return $mainPage;
    }

}