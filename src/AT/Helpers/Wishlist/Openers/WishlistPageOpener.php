<?php
/**
 * @description Открывать страницы избранных товаров
 */

namespace AT\Helpers\Wishlist\Openers;

use AcceptanceCore\Core\WB;
use AT\Page\Wishlist\Wishlist;

class WishlistPageOpener
{
    public function openWishlistPage(): Wishlist
    {
        WB::open('/wishlist');
        $page = new Wishlist();
        $page->waitForReady();
        WB::step('Открылась страница: Избранных товаров проекта Butik');
        return $page;
    }
}