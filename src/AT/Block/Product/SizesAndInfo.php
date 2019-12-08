<?php
/**
 * @description Блок с описанием товара
 */

namespace AT\Block\Product;

use AcceptanceCore\Core\Selector;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;

class SizesAndInfo extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'sizes-and-info';

    private function getSkuSelector(): Selector
    {
        return $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'sku');
    }

    public function getSku(): string
    {
        WB::step("Получение артикула со страницы товара");
        return mb_strtolower(WB::grabTextFrom($this->getSkuSelector()));
    }
}