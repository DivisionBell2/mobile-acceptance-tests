<?php
/**
* @description Тест на проверку работы фильтра по бренду в кталоге.
*/

namespace AT\Test\Mobile\Catalog;

use AT\BaseTest;
use AT\Helpers\Catalog\Openers\CatalogClothesPageOpener;
use AT\Helpers\Catalog\FilterTypeNames;
use AT\Page\Catalog\Clothes;

class CheckWorkingCatalogFilterByBrand extends BaseTest
{
    public function testCheckWorkingWomenCatalogFilterByBrand()
    {
        $clothesPage = (new CatalogClothesPageOpener)->openCatalogWomenClothesPage();
        $this->checkWorkingCatalogFilterByBrand($clothesPage);
    }

    public function testCheckWorkingMenCatalogFilterByBrand()
    {
        $clothesPage = (new CatalogClothesPageOpener)->openCatalogMenClothesPage();
        $this->checkWorkingCatalogFilterByBrand($clothesPage);
    }

    private function checkWorkingCatalogFilterByBrand(Clothes $clothesPage)
    {
        $clothesPage->getCatalogHeader()->getSortBlock()->clickOnFilterButton();
        $filter = $clothesPage->getFilterCategoriesList()->selectAndGetFilterSectionByName(FilterTypeNames::BRAND);
        $checkboxName = $filter->selectCheckboxAndGetNameByIndex();

        $filter->getFilterButtons()->clickOnApplyButtonOnFilterBlock();
        $clothesPage->getFilterCategoriesList()->getFilterButtons()->clickOnApplyButtonOnCategoriesListBlock();
        $productList = $clothesPage->getProductList();

        $this->assertTrue(
            $productList->isProductItemsHaveSameBrandNames($checkboxName),
            'Товары на странице каталога не соответствуют фильтру'
        );
    }
}
