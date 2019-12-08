<?php
/**
 * @description Проверка соответствия информации о товаре в попапе корзины
 */

namespace AT\Test\Mobile\Product;

use AcceptanceCore\Core\Utils\Strings;
use AT\BaseTest;
use AT\Helpers\Product\Openers\ProductPageOpener;

class CheckProductInfoComparsionWithCartPopup extends BaseTest
{
    public function testCheckProductInfoComparsionWithCartPopup()
    {
        $productSizeNumber = 1;
        $productPage = (new ProductPageOpener())->openProductPageByApi();
        $productPage->getProductSizesBlock()->clickAvaliableSizeButton($productSizeNumber);

        $expectedProductBrand = $productPage->getBaseProductInfoBlock()->getProductBrandName();
        $expectedProductPrice = $productPage->getBaseProductInfoBlock()->getProductPrice();
        $expectedProductSize = $productPage->getProductSizesBlock()->getAvaliableSizeButtonBlock($productSizeNumber)->getSizeValue();
        $expectedProductName = $productPage->getBaseProductInfoBlock()->getProductName();

        $cartPopup = $productPage->getAddToUserProfileBlock()->clickAddToCartButtonAndGetAddedToCartPopupBlock();

        $cartPopupBrand = $cartPopup->getProductBrandName();
        $cartPopupPrice = $cartPopup->getProductPrice();
        $cartPopupSize = $cartPopup->getSizeValue();
        $cartPopupProductName = $cartPopup->getProductName();

        $this->assertEquals(
            $expectedProductBrand,
            $cartPopupBrand,
            'Цена на странице товара не совпадает с ценой в попапе корзины'
        );

        $this->assertEquals(
            $expectedProductPrice,
            $cartPopupPrice,
            'Бренд на странице товара не совпадает с брендом в попапе корзины'
        );

        //Добавлено для избегания падения теста на брендовом размере 'one size', не отображающимся в попапе корзины
        if ($expectedProductSize == 'one size') {
            $expectedProductSize = '';
        }
        $this->assertEquals(
            $expectedProductSize,
            $cartPopupSize,
            'Размер на странице товара не совпадает с размером в попапе корзины'
        );

        $this->assertTrue(
            Strings::isStringFoundIn($cartPopupProductName, $expectedProductName),
            'Тип товара на странице не совпадает с типом товара в попапе корзины'
        );
    }
}