<?php
/**
 * @description Тест на заказ товаров авторизованным и неавторизованным пользователем
 */

namespace AT\Test\Mobile\Checkout;

use AT\BaseTest;
use AT\Helpers\Checkout\Openers\CheckoutOpener;
use AT\Page\Checkout\Checkout;

class CheckMakeOrder extends BaseTest
{
    public function testMakeOrderByNotAuthUser()
    {
        $itemsCountForAdd = 3;
        $checkoutPage = (new CheckoutOpener())->openCheckoutPageByApi($itemsCountForAdd);
        $checkoutPage->getAuthorizationQuestionPopup()->clickOnNotNowButton();
        $checkoutPage->getOrderingInformationBlock()->inputCheckoutData();
        $this->checkAssertOrderInfo($checkoutPage);
    }
    public function testMakeOrderByAuthUser()
    {
        $itemsCountForAdd = 3;

        $checkoutPage = (new CheckoutOpener())->openCheckoutPageByApiWithAuthUser($itemsCountForAdd);
        $checkoutOrderingInformationBlock = $checkoutPage->getOrderingInformationBlock();
        $checkoutOrderingInformationBlock->inputCheckoutDataByAuthUser();
        $this->checkAssertOrderInfo($checkoutPage);
    }

    private function checkAssertOrderInfo(Checkout $checkoutPage)
    {
        $totalPrice = $checkoutPage->getOrderingInformationBlock()->getTotalPrice();
        $totalPriceFromCartItems = $checkoutPage->getCartListBlock()->getTotalPriceFromCartItems();

        $this->assertEquals(
            $totalPrice,
            $totalPriceFromCartItems,
            'Полная цена заказа совпадает с суммарной стоимостью товаров в корзине'
        );

        $totalDiscount = $checkoutPage->getOrderingInformationBlock()->getDiscountSum();
        $totalDiscountFromCartItems = $checkoutPage->getCartListBlock()->getTotalDiscountFromCartItems();

        $this->assertEquals(
            $totalDiscount,
            $totalDiscountFromCartItems,
            'Сумма скидки совпадает с суммарной стоимостью скидки товаров в корзине'
        );

        $totalOriginalSumPrice = $checkoutPage->getOrderingInformationBlock()->getOriginalSumPrice();
        $totalOriginalPriceFromCartItems = $checkoutPage->getCartListBlock()->getTotalOriginalPriceFromCartItems();

        $this->assertEquals(
            $totalOriginalSumPrice,
            $totalOriginalPriceFromCartItems,
            'Сумма товаров без скидки совпадает с суммарной стоимостью товаров в корзине без скидки'
        );

        $checkoutFullName = $checkoutPage->getOrderingInformationBlock()->getFullName();
        $checkoutPhoneNumber = $checkoutPage->getOrderingInformationBlock()->getPhoneNumber();

        $successPage = $checkoutPage->getOrderingInformationBlock()->clickMakeOrderButtonAndGoToSuccessfulOrderPage();

        $successOrderFullName = $successPage->getSuccessfulOrderInfoBlock()->getFullName();
        $successOrderPhoneNumber = $successPage->getSuccessfulOrderInfoBlock()->getPhoneNumber();

        $this->assertEquals(
            $checkoutFullName,
            $successOrderFullName,
            'ФИО при оформлении заказа совпадает с ФИО на Success странице'
        );

        $this->assertEquals(
            $checkoutPhoneNumber,
            $successOrderPhoneNumber,
            'Номер телефона при оформлении заказа совпадает с номером телефона на Success странице'
        );
    }
}