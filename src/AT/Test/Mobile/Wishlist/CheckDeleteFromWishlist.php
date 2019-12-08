<?php
/**
 * @description Тест на проверку удаления из вишлиста
 */

namespace AT\Test\Mobile\Wishlist;

use AcceptanceCore\Core\WB;
use AT\BaseTest;
use AT\Block\Wishlist\WishlistItem;
use AT\Helpers\Product\Openers\ProductPageOpener;
use AT\Helpers\Wishlist\Openers\WishlistPageOpener;

class CheckDeleteFromWishlist extends BaseTest
{
    public function testCheckDeleteFromWishlist()
    {
        $productPage = (new ProductPageOpener())->openProductPageByApi();
        $productPage->getAddToUserProfileBlock()->clickAddToFavoriteButton();
        $wishlistPage = (new WishlistPageOpener())->openWishlistPage();
        $urlBeforeClick = WB::getUrl();

        /**
         * @var $wishlisttItems WishlistItem[]
         */
        $wishlisttItems = $wishlistPage->getWishlistItemsBlock()->getWishlistItems();
        $wishlisttItems[0]->clickOnDeleteButton();

        $wishlistPage->getWishlistItemsBlock()->clickOnToSalesButton();
        $urlAfterClick = WB::getUrl();

        $this->assertNotEquals(
            $urlBeforeClick,
            $urlAfterClick,
            "Ссылки после нажатия на кнопку 'Перейти к покупкам' совпадают"
        );
    }
}