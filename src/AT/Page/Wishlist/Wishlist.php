<?php
/**
 * @description Страница избранных товаров
 */

namespace AT\Page\Wishlist;

use AcceptanceCore\Core\Utils\Waiting;
use AT\BasePage;
use AT\Block\Wishlist\WishlistItems;
use AT\Page\iPageTemplate;

class Wishlist extends BasePage implements iPageTemplate
{
    private $wishlistItemsBlock;

    public function getWishlistItemsBlock(): WishlistItems
    {
        if (is_null($this->wishlistItemsBlock)) {
            $this->wishlistItemsBlock = new WishlistItems();
        }
        return $this->wishlistItemsBlock;
    }

    public function waitForReady()
    {
        parent::waitForReady();
        Waiting::waitForElementVisible(
            $this->getWishlistItemsBlock()->me(),
            35,
            'Не удалось дождаться видимости блока карточек товара в wishlist'
        );
    }
}