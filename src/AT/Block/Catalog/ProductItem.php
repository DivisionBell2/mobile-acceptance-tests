<?php
/**
* @description Блок плашки товара на странице каталога
*/

namespace AT\Block\Catalog;

use AT\BaseBlock;
use AcceptanceCore\Core\Selector;
use AcceptanceCore\Core\WB;
use AcceptanceCore\Core\Utils\Strings;
use AT\Block\Link;
use AT\Page\Product\Product;
use AT\Page\Product\ProductItemPage;

class ProductItem extends BaseBlock
{
    private function getBrandNameSelector(): Selector
    {
        return $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'brand-name');
    }

    private function getOriginalPrice(): int
    {
        return (int) Strings::stripAllWhiteSpaces(WB::grabTextFrom($this->me()
            ->childByAttribute(self::TEST_ATTR_NAME, 'original-price')));
    }

    private function getDiscountPrice(): int
    {
        return (int) Strings::stripAllWhiteSpaces(WB::grabTextFrom($this->me()
            ->childByAttribute(self::TEST_ATTR_NAME, 'discount-price')));
    }

    private function getNameSelector(): Selector
    {
        return $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'product-name');
    }

    private function getProductLink(): Link
    {
        return new Link((string) $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'product-link'));
    }

    public function getLinkHref(): string
    {
        return $this->getProductLink();
    }

    public function getProductItemBrandName(): string
    {
        return mb_strtolower(WB::grabTextFrom($this->getBrandNameSelector()));
    }

    public function getProductItemPrice(): int
    {
        if ($this->getDiscountPrice()) {
            return $this->getDiscountPrice();
        }
        return $this->getOriginalPrice();
    }

    public function getProductItemName(): string
    {
        return mb_strtolower(WB::grabTextFrom($this->getNameSelector()));
    }

    public function clickAndGoToProductPage(): Product
    {
        WB::step("Клик по карточке товара");
        $this->getProductLink()->click();
        $product = new Product();
        $product->waitForReady();
        return $product;
    }
}