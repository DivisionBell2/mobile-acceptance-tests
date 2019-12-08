<?php
/**
* @description Блок списка товаров
*/

namespace AT\Block\Catalog;

use AT\BaseBlock;
use AcceptanceCore\Core\WB;
use AcceptanceCore\Core\ATException;
use Core\Utils\Arrays;
use AcceptanceCore\Core\Utils\Strings;

class ProductList extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'product-list';

    private $moreItemsButtonsBlock;
    private $productsArr = [];

    public function getMoreItemsButtonsBlock(): MoreItemsButtons
    {
        if (is_null($this->moreItemsButtonsBlock)) {
            $this->moreItemsButtonsBlock = new MoreItemsButtons();
        }
        return $this->moreItemsButtonsBlock;
    }

    public function getProductItems(bool $useCache = true): array
    {
        if ($useCache && count($this->productsArr) > 0) {
            return $this->productsArr;
        }

        $productsArr = [];
        $productsSelector = $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'product-item');
        $productsSelectorsCount = WB::selectorCount($productsSelector);
        for ($i = 1; $i <= $productsSelectorsCount; $i++) {
            $selector = $productsSelector->nthOfType($i);
            if (WB::isElementExists($selector)) {
                $productsArr[] = new ProductItem((string) $selector);
            }
        }
        Arrays::checkForEmptyArray($productsArr, 'Не найдены товары в каталоге');
        $this->productsArr = $productsArr;
        return $productsArr;
    }

    public function isProductItemsHaveSameBrandNames(string $expectedBrandName): bool
    {
        WB::step("Проверка на то, что все товары на странице относятся к одному бренду");
        $productItemsArr = $this->getProductItems();

        /**
         * @var $productItem ProductItem
         */
        foreach($productItemsArr as $productItem) {
            $productBrandName = $productItem->getProductItemBrandName();
            if (!Strings::areStringsEqualCi($productBrandName, $expectedBrandName)) {
                throw new ATException("Имя бренда товара {$productBrandName} не соответствует ожидаемому {$expectedBrandName}");
            }
        }
        return true;
    }

    public function isProductItemsExistingOnProductList(): bool
    {
        WB::step("Проверка на то, что на странице присутствуют товары");
        if(count($this->getProductItems(false)) > 0) {
            return true;
        }
        throw new ATException("Товары на странице отсутствуют");
    }

    public function isProductItemsHavePriceInRange(int $minValue, int $maxValue): bool
    {
        WB::step("Проверка на соответствие цен отображаемых товаров выбранному фильтру");
        $productItemsArr = $this->getProductItems();

        /**
         * @var $productItem ProductItem
         */
        foreach($productItemsArr as $productItem) {
            $productItemPrice = $productItem->getProductItemPrice();
            if ($productItemPrice >= $minValue && $productItemPrice <= $maxValue) {
                return true;
            }
        }
        throw new ATException("Цена товаров не соответствует диапазону цен фильтра от {$minValue} до {$maxValue}");
    }
}