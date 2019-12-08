<?php
/**
 * @description Блок избранных товаров
 */

namespace AT\Block\Wishlist;

use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\Button;
use AT\Page\Main\Main;
use Core\Utils\Arrays;

class WishlistItems extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'wishlist-items';

    private $wishlistItems = [];

    private function getGoToSalesButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'go-to-sales-button'));
    }

    public function getWishlistItems(bool $useCache = true): array
    {
        if ($useCache && count($this->wishlistItems) > 0) {
            return $this->wishlistItems;
        }

        $wishlistItems = [];
        $wishlistItemSelector = $this->me()->childByTag('div', true);
        $wishlistItemsSelectorsCount = WB::selectorCount($wishlistItemSelector);
        for ($i = 1; $i <= $wishlistItemsSelectorsCount; $i++) {
            $selector = $wishlistItemSelector->nthOfType($i);
            if (WB::isElementExists($selector->childByAttribute(self::TEST_ATTR_NAME, 'wishlist-item'))) {
                $wishlistItems[] = new WishlistItem((string) $selector);
            }
        }
        Arrays::checkForEmptyArray($wishlistItems, 'Карточки товаров отсутствуют в списке Избранного');
        $this->wishlistItems = $wishlistItems;
        return $wishlistItems;
    }

    public function clickOnToSalesButton(): Main
    {
        $goToSalesButton = $this->getGoToSalesButton();
        Waiting::waitForElementVisible(
            $goToSalesButton->me(),
            3,
            "Кнопка 'Перейкти к покупкам' отсутствует на экране"
        );
        WB::step("Клик по кнопке 'Перейти к покупкам' и переход в каталог");

        $goToSalesButton->click();
        $mainPage = new Main();
        $mainPage->waitForReady();
        return $mainPage;
    }
}