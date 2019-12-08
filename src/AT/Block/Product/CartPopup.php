<?php
/**
 * @description Блок попапа корзины
 */

namespace AT\Block\Product;

use AcceptanceCore\Core\Selector;
use AcceptanceCore\Core\Utils\Strings;
use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\Button;
use AT\Page\Checkout\Checkout;

class CartPopup extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'item-add-dialog';

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

    private function getBrandNameSelector(): Selector
    {
        return $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'product-brand-name');
    }

    private function getSizeSelector(): Selector
    {
        return $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'product-size');
    }

    private function getProductNameSelector(): Selector
    {
        return $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'product-type');
    }

    private function getGoToCartButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'go-to-cart-button'));
    }

    public function getProductPrice(): int
    {
        if ($this->getDiscountPrice()) {
            return $this->getDiscountPrice();
        }
        return $this->getOriginalPrice();
    }

    public function getProductBrandName(): string
    {
        return mb_strtolower(WB::grabTextFrom($this->getBrandNameSelector()));
    }

    public function getProductName(): string
    {
        return mb_strtolower(WB::grabTextFrom($this->getProductNameSelector()));
    }

    public function getSizeValue(): string
    {
        $sizeValue = mb_strtolower(WB::grabTextFrom($this->getSizeSelector()));
        $sizeValue = str_replace('brand ', '', $sizeValue);
        $sizeValue = str_replace('rus ', '', $sizeValue);
        return $sizeValue;
    }

    public function clickOnGoToCartButton(): Checkout
    {
        WB::step("Клик по кнопке 'Оформить заказ' и переход в корзину");
        $this->getGoToCartButton()->click();
        $checkoutPage = new Checkout();
        $checkoutPage->waitForReady();
        return $checkoutPage;
    }

    public function waitForReady()
    {
        Waiting::waitForElementVisible(
            $this->me(),
            10,
            'Не удалось дождаться видимости попапа корзины'
        );
    }
}