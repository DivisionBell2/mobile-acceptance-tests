<?php
/**
 * @description Список товаров в корзине
 */

namespace AT\Block\Checkout;

use AcceptanceCore\Core\ATException;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use Core\Utils\Arrays;

class CartList extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'checkout-list';

    private $cartItems = [];

    public function getСartItems(bool $useCache = false): array
    {
        if ($useCache && count($this->cartItems) > 0) {
            return $this->cartItems;
        }

        $cartItems = [];
        $checkoutItemsSelector = $this->me()->childByTag('div', true);
        $checkoutItemsSelectorsCount = WB::selectorCount($checkoutItemsSelector);
        for ($i = 1; $i <= $checkoutItemsSelectorsCount; $i++) {
            $selector = $checkoutItemsSelector->nthOfType($i);
            if (WB::isElementExists($selector->childByAttribute(self::TEST_ATTR_NAME, 'cart-item'))) {
                $cartItems[] = new CartItem((string) $selector);
            }
        }
        Arrays::checkForEmptyArray($cartItems, 'Карточки товаров отсутствуют в корзине');
        $this->cartItems = $cartItems;
        return $cartItems;
    }

    public function getTotalPriceFromCartItems(): int
    {
        WB::step("Получение суммарной цены со всех карточек товаров");
        /**
         * @var $cardItems CartItem[]
         */
        $cardItems = $this->getСartItems();
        $totalPrice = 0;
        for ($i = 0; $i < count($cardItems); $i++) {
            if ($cartPrice = $cardItems[$i]->getDiscountPrice()) {
                $totalPrice += $cartPrice;
            } else {
                throw new ATException("На карточке товара отсутствует цена");
            }
        }
        return $totalPrice;
    }

    public function getTotalDiscountFromCartItems(): int
    {
        WB::step("Получение суммарной скидки со всех карточек товаров");
        /**
         * @var $cardItems CartItem[]
         */
        $cardItems = $this->getСartItems();
        $totalDiscount = 0;
        for ($i = 0; $i < count($cardItems); $i++) {
            if ($cartPrice = $cardItems[$i]->getDiscountPrice()) {

                if ($cartOriginalPrice = $cardItems[$i]->getOriginalPrice()) {
                    $totalDiscount += $cartOriginalPrice - $cartPrice;
                }

            } else {
                throw new ATException("На карточке товара отсутствует цена");
            }
        }
        return $totalDiscount;
    }

    public function getTotalOriginalPriceFromCartItems(): int
    {
        WB::step("Получение суммарной цены без скидки со всех карточек товаров");
        /**
         * @var $cardItems CartItem[]
         */
        $cardItems = $this->getСartItems();
        $totalOriginalPrice = 0;
        for ($i = 0; $i < count($cardItems); $i++) {
            if ($cartPrice = $cardItems[$i]->getFullPrice()) {
                $totalOriginalPrice += $cartPrice;
            } else {
                throw new ATException("На карточке товара отсутствует цена");
            }
        }
        return $totalOriginalPrice;
    }
}