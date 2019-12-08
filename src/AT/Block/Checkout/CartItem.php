<?php
/**
 * @description Блок карточки товара в корзине
 */

namespace AT\Block\Checkout;

use AcceptanceCore\Core\Selector;
use AcceptanceCore\Core\Utils\Strings;
use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\Button;

class CartItem extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'cart-item';

    private function getBrandSelector(): Selector
    {
        return $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'brand');
    }

    private function getSkuSelector(): Selector
    {
        return $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'sku');
    }

    private function getDeleteItemButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'delete-button'));
    }

    public function getOriginalPrice(): int
    {
        return (int) Strings::stripAllWhiteSpaces(WB::grabTextFrom($this->me()
            ->childByAttribute(self::TEST_ATTR_NAME, 'original-price')));
    }

    public function getDiscountPrice(): int
    {
        return (int) Strings::stripAllWhiteSpaces(WB::grabTextFrom($this->me()
            ->childByAttribute(self::TEST_ATTR_NAME, 'discount-price')));
    }

    public function getFullPrice(): int
    {
        if ($originalPrice = $this->getOriginalPrice()) {
            return $originalPrice;
        }
        return $this->getDiscountPrice();
    }

    public function getBrand(): string
    {
        WB::step("Получение имени бренда с карточки товара в корзине");
        return mb_strtolower(WB::grabTextFrom($this->getBrandSelector()));
    }

    public function getSku(): string
    {
        WB::step("Получение артикула с карточки товара в корзине");
        $skuName = mb_strtolower(WB::grabTextFrom($this->getSkuSelector()));
        $skuName = str_replace('артикул ', '', $skuName);
        return $skuName;
    }

    public function clickOnDeleteButton()
    {
        WB::step("Клик по кнопке 'Удалить' и ожидание удаления карточки товара из корзины");
        $this->getDeleteItemButton()->click();
        Waiting::waitForAjax(3);
    }
}