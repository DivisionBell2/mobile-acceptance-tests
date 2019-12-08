<?php
/**
 * @description Тест на проверку перехода из главного меню в каталог
 */

namespace AT\Test\Mobile\MainMenu;

use AT\BaseTest;
use AT\Helpers\HeaderMenu\MenuCategoryNames;
use AT\Helpers\Main\Openers\MainPageOpener;
use AT\Page\Catalog\Shoes;

class CheckTransitionOnMainMenu extends BaseTest
{
    public function testCheckTransitionOnMainMenu()
    {
        $categoryName = MenuCategoryNames::SHOES;

        $mainPage = (new MainPageOpener())->openMainPage();
        $mainMenu = $mainPage->openAndGetMainMenuBlock();
        $mainMenu->getCategoriesList()->clickOnCategoryName($categoryName);
        $newProducts = $mainMenu->getCategoriesList()
            ->clickOnSubcategoryAndGoToCatalogPage(new Shoes(), 1);

        $this->assertTrue(
            $newProducts->getProductList()->isProductItemsExistingOnProductList(),
            "Карточки товаров отсутствуют на странице {$categoryName}"
        );
    }
}