<?php
/**
 * @description Тест на проверку работы фильтра по цене в каталоге.
 */

namespace AT\Test\Mobile\Catalog;

use AT\BaseTest;
use AT\Block\Catalog\FilterPrice;
use AT\Helpers\Catalog\FilterTypeNames;
use AT\Helpers\Catalog\Openers\CatalogClothesPageOpener;
use AT\Page\Catalog\Clothes;

class CheckWorkingCatalogFilterByPrice extends BaseTest
{
    public function testCheckWorkingCatalogFilterByPriceInWomenCatalog()
    {
        $clothesPage = (new CatalogClothesPageOpener)->openCatalogWomenClothesPage();
        $this->checkWorkingPriceFilter($clothesPage);
    }

    public function testCheckWorkingCatalogFilterByPriceInMenCatalog()
    {
        $clothesPage = (new CatalogClothesPageOpener)->openCatalogMenClothesPage();
        $this->checkWorkingPriceFilter($clothesPage);
    }

    private function checkWorkingPriceFilter(Clothes $clothesPage)
    {
        $minValue = 4000;
        $maxValue = 8000;

        $clothesPage->getCatalogHeader()->getSortBlock()->clickOnFilterButton();
        $clothesPage->getFilterCategoriesList()->selectAndGetFilterSectionByName(FilterTypeNames::PRICE);
        $filterPrice = new FilterPrice();
        $filterPrice->setFilterPriceValues($minValue, $maxValue);
        $filterPrice->getFilterButtons()->clickOnApplyButtonOnFilterBlock($filterPrice);
        $clothesPage->getFilterCategoriesList()->getFilterButtons()->clickOnApplyButtonOnCategoriesListBlock();

        $this->assertTrue(
            $clothesPage->getProductList()->isProductItemsHavePriceInRange($minValue, $maxValue),
            "Цена товаров не соответствует диапазону цен фильтра от {$minValue} до {$maxValue}"
        );
    }
}
