<?php
/**
 * @description Тест на удаление товаров из корзины
 */

namespace AT\Test\Mobile\Checkout;

use AcceptanceCore\Core\WB;
use AT\BaseTest;
use AT\Block\Checkout\CartItem;
use AT\Helpers\Checkout\Openers\CheckoutOpener;

class CheckDeleteFromCart extends BaseTest
{
    public function testDeleteFromCart()
    {
        $itemsCountForAdd = 3;
        $checkoutPage = (new CheckoutOpener())->openCheckoutPageByApi($itemsCountForAdd);
        $expectedItemsCount = $itemsCountForAdd;
        /**
         * @var $cartItems CartItem[]
         */
        for ($i = 1; $i <= $itemsCountForAdd; $i++) {
            $expectedItemsCount--;
            $cartItems = $checkoutPage->getCartListBlock()->getСartItems();
            $cartItems[0]->clickOnDeleteButton();
            $isEmptyCart = $i == $itemsCountForAdd;
            if (!$isEmptyCart) {
                $this->assertCount(
                    $expectedItemsCount,
                    $checkoutPage->getCartListBlock()->getСartItems(),
                    'Товар не был удален из корзины'
                );
            } else {
                $urlBeforeClick = WB::getUrl();
                $checkoutPage->getCartContentBlock()->clickOnToSalesButton();
                $urlAfterClick = WB::getUrl();

                $this->assertNotEquals(
                    $urlBeforeClick,
                    $urlAfterClick,
                    "Ссылки после нажатия на кнопку 'Перейти к покупкам' совпадают"
                );
            }
        }
    }
}