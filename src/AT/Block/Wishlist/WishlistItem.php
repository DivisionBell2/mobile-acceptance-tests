<?php
/**
 * @description Блок товара, добавленного в избранное
 */

namespace AT\Block\Wishlist;

use AcceptanceCore\Core\Selector;
use AcceptanceCore\Core\Utils\Strings;
use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\Button;

class WishlistItem extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'wishlist-item';

    private function getBrandSelector(): Selector
    {
        return $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'brand-name');
    }

    private function getProductNameSelector(): Selector
    {
        return $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'product-name');
    }

    private function getDeleteItemButton(): Button
    {
        return new Button((string) $this->me()
            ->childByAttribute(self::TEST_ATTR_NAME, 'mobile-delete-button-container')
            ->childByAttribute(self::TEST_ATTR_NAME, 'delete-button'));
    }

    public function getBrand(): string
    {
        WB::step("Получение имени бренда с карточки товара в Избранном");
        return mb_strtolower(WB::grabTextFrom($this->getBrandSelector()));
    }

    public function getProductName(): string
    {
        WB::step("Получение названия товара с карточки в Избранном");
        return mb_strtolower(WB::grabTextFrom($this->getProductNameSelector()));
    }

    public function getFullPrice(): int
    {
        return (int) Strings::stripAllWhiteSpaces(WB::grabTextFrom($this->me()
            ->childByAttribute(self::TEST_ATTR_NAME, 'original-price')));
    }

    public function getDiscountPrice(): int
    {
        return (int) Strings::stripAllWhiteSpaces(WB::grabTextFrom($this->me()
            ->childByAttribute(self::TEST_ATTR_NAME, 'discount-price')));
    }

    public function getPrice(): int
    {
        WB::step("Получение текущей цены с карточки товара в Избранном");
        if ($discountPrice = $this->getDiscountPrice()) {
            return $discountPrice;
        }
        return $this->getFullPrice();
    }

    public function clickOnDeleteButton()
    {
        WB::step("Клик по кнопке 'Удалить' и ожидание удаления карточки товара из избранного");
        $this->getDeleteItemButton()->click();
        Waiting::waitForAjax(3);
    }
}