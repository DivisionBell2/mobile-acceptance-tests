<?php
/**
 * @description Блок базовой информации на странице товара
 */

namespace AT\Block\Product;

use AcceptanceCore\Core\Selector;
use AcceptanceCore\Core\Utils\Strings;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;

class ProductInfo extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'brand-and-type';

    private function getBrandNameSelector(): Selector
    {
        return $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'brand');
    }

    private function getNameSelector(): Selector
    {
        return $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'name');
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

    public function getProductBrandName(): string
    {
        return mb_strtolower(WB::grabTextFrom($this->getBrandNameSelector()));
    }

    public function getProductName(): string
    {
        return mb_strtolower(WB::grabTextFrom($this->getNameSelector()));
    }

    public function getProductPrice(): int
    {
        if ($this->getDiscountPrice()) {
            return $this->getDiscountPrice();
        }
        return $this->getOriginalPrice();
    }
}