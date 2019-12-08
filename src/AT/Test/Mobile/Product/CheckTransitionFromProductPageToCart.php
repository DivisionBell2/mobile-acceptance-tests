<?php
/**
 * @description Тест проверяет переход из страницы товара в корзину, и сверяет данные на двух страницах
 */

namespace AT\Test\Mobile\Product;

use AT\BaseTest;
use AT\Helpers\Product\Openers\ProductPageOpener;

class CheckTransitionFromProductPageToCart extends BaseTest
{
    public function testCheckTransitionFromProductPageToCart()
    {
        $productSizeNumber = 1;
        $productPage = (new ProductPageOpener())->openProductPageByApi();
        $productPage->getProductSizesBlock()->clickAvaliableSizeButton($productSizeNumber);

        $expectedProductBrand = $productPage->getBaseProductInfoBlock()->getProductBrandName();
        $expectedProductPrice = $productPage->getBaseProductInfoBlock()->getProductPrice();
        $expectedProductSku = $productPage->getSizeAndInfoBlock()->getSku();

        $cartPopup = $productPage->getAddToUserProfileBlock()->clickAddToCartButtonAndGetAddedToCartPopupBlock();
        $checkoutPage = $cartPopup->clickOnGoToCartButton();
        $cartItem = $checkoutPage->getCartListBlock()->getСartItems()[0];

        $this->assertEquals(
            $expectedProductBrand,
            $cartItem->getBrand(),
            'Бренд на странице товара не совпадает с брендом товара в корзине'
        );

        $this->assertEquals(
            $expectedProductSku,
            $cartItem->getSku(),
            'Артикул на странице товара не совпадает с артикулом товара в корзине'
        );

        $this->assertEquals(
            $expectedProductPrice,
            $cartItem->getDiscountPrice(),
            'Цена на странице товара не совпадает с ценой товара в корзине'
        );
    }
}