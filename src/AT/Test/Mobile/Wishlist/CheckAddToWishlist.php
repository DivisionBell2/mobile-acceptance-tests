<?php
/**
 * @description Тест на проверку добавления товара в вишлист
 */

namespace AT\Test\Mobile\Wishlist;

use AcceptanceCore\Core\Utils\Strings;
use AT\BaseTest;
use AT\Helpers\Product\Openers\ProductPageOpener;
use AT\Helpers\Wishlist\Openers\WishlistPageOpener;

class CheckAddToWishlist extends BaseTest
{
    public function testCheckAddToWishlist()
    {
        $productPage = (new ProductPageOpener())->openProductPageByApi();
        $productPage->getAddToUserProfileBlock()->clickAddToFavoriteButton();
        $baseProductInfoBlock = $productPage->getBaseProductInfoBlock();
        
        $expectedProductBrand = $baseProductInfoBlock ->getProductBrandName();
        $expectedProductPrice = $baseProductInfoBlock ->getProductPrice();
        $expectedProductName = $baseProductInfoBlock ->getProductName();

        $wishlistPage = (new WishlistPageOpener())->openWishlistPage();
        $wishlisttItem = $wishlistPage->getWishlistItemsBlock()->getWishlistItems()[0];

        $this->assertEquals(
            $expectedProductBrand,
            $wishlisttItem->getBrand(),
            'Бренд на странице товара не совпадает с брендом товара в Избранном'
        );

        $this->assertEquals(
            $expectedProductPrice,
            $wishlisttItem->getPrice(),
            'Цена на странице товара не совпадает с ценой товара в Избранном'
        );

        $this->assertTrue(
            Strings::isStringFoundIn($wishlisttItem->getProductName(), $expectedProductName),
            'Имя товара на странице не совпадает с именем товара в Избранном'
        );
    }
}