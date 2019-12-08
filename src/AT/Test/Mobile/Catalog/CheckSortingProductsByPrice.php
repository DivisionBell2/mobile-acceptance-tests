<?php
/**
 * @description Тест на проверку сортировки товаров в каталоге по цене
 */

namespace AT\Test\Mobile\Catalog;

use AT\BaseTest;
use AT\Helpers\Catalog\Openers\CatalogClothesPageOpener;
use AT\Helpers\Catalog\SortByNames;
use AT\Page\Catalog\Clothes;
use Core\Utils\Arrays;

class CheckSortingProductsByPrice extends BaseTest
{
    public function testCheckSortingProductsByPrice()
    {
        $clothesPage = (new CatalogClothesPageOpener)->openCatalogWomenClothesPage();
        $productItemsPrices = $this->getSortedItemsPrices($clothesPage, SortByNames::SORT_ASCENDING_PRICE);

        for ($i = 1; $i < count($productItemsPrices); $i++) {
            $productItemPriceCurrent = $productItemsPrices[$i - 1];
            $productItemPriceNext = $productItemsPrices[$i];

            $this->assertGreaterThanOrEqual(
                $productItemPriceCurrent,
                $productItemPriceNext,
                "Сортировка по возрастанию цены некорректна"
            );
        }

        $productItemsPrices = $this->getSortedItemsPrices($clothesPage, SortByNames::SORT_DESCENDING_PRICE);

        for ($i = 1; $i < count($productItemsPrices); $i++) {
            $productItemPriceCurrent = $productItemsPrices[$i - 1];
            $productItemPriceNext = $productItemsPrices[$i];

            $this->assertLessThanOrEqual(
                $productItemPriceCurrent,
                $productItemPriceNext,
                "Сортировка по убыванию цены некорректна"
            );
        }
    }

    private function getSortedItemsPrices(Clothes $clothesPage, string $sortBy): array
    {
        $sortBlock = $clothesPage->getCatalogHeader()->getSortBlock()->clickOnSortButton();
        $clothesPage = $sortBlock->clickOnSortButton($sortBy);
        $productItems = $clothesPage->getProductList()->getProductItems();

        $productItemsPrices = [];
        for ($i = 0; $i < count($productItems); $i++) {
            $productItemsPrices[$i] = $productItems[$i]->getProductItemPrice();
        }

        Arrays::checkForEmptyArray($productItemsPrices, 'Цены товаров отсутствуют в массиве');
        return $productItemsPrices;
    }
}