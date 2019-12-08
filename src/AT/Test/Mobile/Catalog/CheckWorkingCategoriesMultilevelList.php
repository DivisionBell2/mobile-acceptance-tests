<?php
/**
 * @description Тест на проверку работы перехода по категориям внутри каталога
 */

namespace AT\Test\Mobile\Catalog;

use AcceptanceCore\Core\WB;
use AcceptanceCore\Logger;
use AT\BaseTest;
use AT\Helpers\Catalog\Openers\CatalogClothesPageOpener;
use AT\Page\Catalog\Clothes;

class CheckWorkingCategoriesMultilevelList extends BaseTest
{
    public function testCheckWorkingCategoriesMultilevelListInWomenCatalog()
    {
        $clothesPage = (new CatalogClothesPageOpener)->openCatalogWomenClothesPage();
        $this->checkWorkingCategoriesMultilevelList($clothesPage);
    }

    public function testCheckWorkingCategoriesMultilevelListInMenCatalog()
    {
        $clothesPage = (new CatalogClothesPageOpener)->openCatalogMenClothesPage();
        $this->checkWorkingCategoriesMultilevelList($clothesPage);
    }

    private function checkWorkingCategoriesMultilevelList(Clothes $clothesPage)
    {
        $checkCounter = 0;
        $urlBeforeClick = WB::getUrl();
        $categoriesListBlock = $clothesPage->getCatalogHeader()->clickOnGenderCategoriesButton();
        $categoriesList = $categoriesListBlock->getCategories();

        foreach ($categoriesList as $categoryName => $category) {
            $categoriesListBlock->clickOnCategoryAndGoToCatalog($clothesPage, $categoryName);

            $this->assertTrue(
                $clothesPage->getProductList()->isProductItemsExistingOnProductList(),
                'На странице отсутствуют товары'
            );

            $this->assertEquals(
                $categoryName,
                $clothesPage->getCatalogHeader()->getNameCategory(),
                'Имя выбранной категории не совпадает с заголовком в каталоге'
            );

            $urlAfterClick = WB::getUrl();
            $this->assertNotEquals(
                $urlBeforeClick,
                $urlAfterClick,
                'Ссылки после перехода на другую категорию совпадают'
            );

            $checkCounter++;
            if ($checkCounter == 4) {
                Logger::log("Проверено {$checkCounter} категорий товаров");
                return;
            }

            $urlBeforeClick = $urlAfterClick;
            $clothesPage->getCatalogHeader()->clickOnGenderCategoriesButton();
        }
    }
}