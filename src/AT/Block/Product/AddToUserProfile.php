<?php
/**
 * @description Блок добавления товара в избранное и в корзину
 */

namespace AT\Block\Product;

use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\Button;

class AddToUserProfile extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'addToUserProfile';

    private function getAddToCartButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'add-to-cart'));
    }

    private function getAddToFavoriteButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'add-to-favorite'));
    }

    public function clickAddToCartButtonAndGetAddedToCartPopupBlock(): CartPopup
    {
        WB::step("Клик по кнопке 'Добавить в корзину'");
        $this->getAddToCartButton()->click();
        $cartPopup = new CartPopup();
        $cartPopup->waitForReady();
        return $cartPopup;
    }

    public function clickAddToFavoriteButton()
    {
        WB::step("Клик по кнопке 'Добавить в Избранное'");
        $this->getAddToFavoriteButton()->click();
    }
}