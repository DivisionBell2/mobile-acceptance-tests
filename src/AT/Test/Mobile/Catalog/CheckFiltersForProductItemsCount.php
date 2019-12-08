<?php
/**
 * @description Тест на проверку работы фильтра по бренду в кталоге.
 */

namespace AT\Test\Mobile\Catalog;

use AcceptanceCore\Core\Utils\Waiting;
use AT\BaseTest;
use AT\Block\Catalog\Filter;
use AT\Block\Catalog\FilterPrice;
use AT\Helpers\Catalog\Openers\CatalogClothesPageOpener;
use AT\Page\Catalog\Clothes;

class CheckFiltersForProductItemsCount extends BaseTest
{
    public function testCheckFiltersForProductItemsCountInWomenCatalog()
    {
        $clothesPage = (new CatalogClothesPageOpener)->openCatalogWomenClothesPage();
        $this->checkFiltersForProductItemsCount($clothesPage);
    }

    public function testCheckFiltersForProductItemsCountInMenCatalog()
    {
        $clothesPage = (new CatalogClothesPageOpener)->openCatalogMenClothesPage();
        $this->checkFiltersForProductItemsCount($clothesPage);
    }

    private function checkFiltersForProductItemsCount(Clothes $clothesPage)
    {
        $clothesPage->getCatalogHeader()->getSortBlock()->clickOnFilterButton();
        $filterSections = $clothesPage->getFilterCategoriesList()->getFilterSectionsLinks();

        foreach ($filterSections as $filterKey => $filterSection) {
            $filterSection = $clothesPage->getFilterCategoriesList()->selectAndGetFilterSectionByName($filterKey);

            if ($filterSection instanceof FilterPrice) {
                $this->checkPriceFilter($clothesPage, $filterKey, $filterSection);
            } else {
                $this->checkCheckboxFilters($clothesPage, $filterKey, $filterSection);
            }
        }
    }

    private function checkCheckboxFilters(Clothes $clothesPage, string $filterKey, Filter $filterSection)
    {
        $filterCheckboxes = $filterSection->getFilterCheckboxes();
        $checkboxesLoopCount = 0;

        foreach ($filterCheckboxes as $keyCheckbox => $filterCheckbox) {
            if ($checkboxesLoopCount >= 2) {
                break;
            }
            $filterSection->selectCheckboxAndGetNameByIndex($keyCheckbox);
            $filterSection->getFilterButtons()->clickOnApplyButtonOnFilterBlock();
            $clothesPage->getFilterCategoriesList()->getFilterButtons()->clickOnApplyButtonOnCategoriesListBlock();

            $this->assertTrue(
                $clothesPage->getProductList()->isProductItemsExistingOnProductList(),
                'Товары отсутствуют на странице'
            );

            $clothesPage->getCatalogHeader()->getSortBlock()->clickOnFilterButton();
            $filterSection = $clothesPage->getFilterCategoriesList()->selectAndGetFilterSectionByName($filterKey);
            $filterSection->getFilterButtons()->clickOnResetButton();
            $checkboxesLoopCount++;
        }

        $filterSection->getFilterButtons()->clickOnApplyButtonOnFilterBlock();
    }

    private function checkPriceFilter(Clothes $clothesPage, string $filterKey, FilterPrice $filterSection)
    {
        $filterSection->setFilterPriceValues();
        $filterSection->getFilterButtons()->clickOnApplyButtonOnFilterBlock($filterSection);
        $clothesPage->getFilterCategoriesList()->getFilterButtons()->clickOnApplyButtonOnCategoriesListBlock();

        $this->assertTrue(
            $clothesPage->getProductList()->isProductItemsExistingOnProductList(),
            'Товары отсутствуют на странице'
        );

        $clothesPage->getCatalogHeader()->getSortBlock()->clickOnFilterButton();
        $filterSection = $clothesPage->getFilterCategoriesList()->selectAndGetFilterSectionByName($filterKey);
        $filterSection->getFilterButtons()->clickOnResetButton();
        $filterSection->getFilterButtons()->clickOnApplyButtonOnFilterBlock();
    }
}
