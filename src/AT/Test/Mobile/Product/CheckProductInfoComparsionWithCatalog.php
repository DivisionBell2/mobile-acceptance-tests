<?php
/**
 * @description  Тест на соответствие информации о товаре в каталоге и на странице товара
 */

namespace AT\Test\Mobile\Product;

use AT\BaseTest;
use AT\Block\Catalog\ProductItem;
use AT\Helpers\Catalog\Openers\CatalogClothesPageOpener;
use AT\Page\Catalog\Clothes;

class CheckProductInfoComparsionWithCatalog extends BaseTest
{
    public function testCheckProductInfoComparsionWithWomenCatalog()
    {
        $clothesPage = (new CatalogClothesPageOpener)->openCatalogWomenClothesPage();
        $this->checkProductInfoComparsionWithCatalog($clothesPage);
    }

    public function testCheckProductInfoComparsionWithMenCatalog()
    {
        $clothesPage = (new CatalogClothesPageOpener)->openCatalogMenClothesPage();
        $this->checkProductInfoComparsionWithCatalog($clothesPage);
    }

    private function checkProductInfoComparsionWithCatalog(Clothes $clothesPage)
    {
        /**
         * @var $productItem ProductItem
         */
        $productItem = $clothesPage->getProductList()->getProductItems()[0];
        $productItemBrand = $productItem->getProductItemBrandName();
        $productItemName = $productItem->getProductItemName();
        $productItemPrice = $productItem->getProductItemPrice();
        $productPage = $productItem->clickAndGoToProductPage();
        $productInfo = $productPage->getBaseProductInfoBlock();

        $this->assertEquals(
            $productItemBrand,
            $productInfo->getProductBrandName(),
            'Бренд на странице товара и в каталоге не совпадает'
        );

        $this->assertEquals(
            $productItemName,
            $productInfo->getProductName(),
            'Названия продукта на странице товара и в каталоге не совпадают'
        );

        $this->assertEquals(
            $productItemPrice,
            $productInfo->getProductPrice(),
            'Цены на странице товара и в каталоге не совпадает'
        );
    }
}