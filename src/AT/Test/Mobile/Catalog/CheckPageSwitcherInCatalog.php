<?php
/**
 * @description Тест на проверку работы пагинации в каталоге.
 */

namespace AT\Test\Mobile\Catalog;

use AT\BaseTest;
use AT\Helpers\Catalog\Openers\CatalogClothesPageOpener;
use AcceptanceCore\Core\WB;

class CheckPageSwitcherInCatalog extends BaseTest
{
    public function testCheckPageSwitcherInCatalog()
    {
        $clothesPage = (new CatalogClothesPageOpener)->openCatalogWomenClothesPage();

        for ($pageLinkNumber = 2; $pageLinkNumber <= 4; $pageLinkNumber++) {
            $urlBeforeClick = WB::getUrl();
            $clothesPage->getProductList()->getMoreItemsButtonsBlock()->clickOnPageLink($pageLinkNumber);
            $urlAfterClick = WB::getUrl();

            $this->assertTrue(
                $urlBeforeClick != $urlAfterClick,
                "Ссылки после переключения страниц совпадают"
            );

            $this->assertTrue(
                $clothesPage->getProductList()->isProductItemsExistingOnProductList(),
                "На странице отсутствуют товары"
            );

            $this->assertTrue(
                $clothesPage->getProductList()->getMoreItemsButtonsBlock()->isPageLinkSelected($pageLinkNumber),
                "В пагинаторе не выделена текущая страница"
            );
        }
    }
}
