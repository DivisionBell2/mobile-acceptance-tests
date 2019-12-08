<?php
/**
 * @description Тест на проверку работы кнопки "Показать еще" в каталоге.
 */

namespace AT\Test\Mobile\Catalog;

use AT\BaseTest;
use AT\Helpers\Catalog\Openers\CatalogClothesPageOpener;

class CheckShowMoreButtonInCatalog extends BaseTest
{
    public function testCheckShowMoreButtonInCatalog()
    {
        $clothesPage = (new CatalogClothesPageOpener)->openCatalogWomenClothesPage();
        $this->assertTrue(
            $clothesPage->getProductList()->getMoreItemsButtonsBlock()->clickShowMoreButtonAndWaitForLoadCatalogItems(),
            "После клика по кнопке 'Показать еще' количество товаров не изменилось"
        );
    }
}


