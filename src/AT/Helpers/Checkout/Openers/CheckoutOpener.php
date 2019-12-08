<?php
/**
 * @description Добавляет товар в корзину и открывает корзину
 */

namespace AT\Helpers\Checkout\Openers;

use AcceptanceCore\Core\Env;
use AcceptanceCore\Core\WB;
use AT\Helpers\Checkout\AddToCartApiHelper;
use AT\Helpers\Main\Openers\MainPageOpener;
use AT\Helpers\RegisterUser\RegisteredUser;
use AT\Helpers\RegisterUser\UserDataGenerator;
use AT\Helpers\UserProfile\Openers\UserProfilePageOpener;
use AT\Page\Checkout\Checkout;
use GuzzleHttp\Client;

class CheckoutOpener
{
    public function openCheckoutPageByApi(int $itemsCountForAdd = 1): Checkout
    {
        $sessionCookieValue = (new AddToCartApiHelper())->addItemsToCart($itemsCountForAdd);
        WB::addCookieToDomain('session', $sessionCookieValue, Env::getInstance()->domain());
        return $this->openCheckoutPage();
    }

    public function openCheckoutPageByApiWithAuthUser(int $itemsCountForAdd = 1): Checkout
    {
        $guzzleClient = new Client([
            'defaults' => ['verify' => false],
            'cookies' => true,
            'headers' => [
                'X-Request-ID' => '1bcdd77ea071619c83d84acf0bb7eb19',
            ]
        ]);

        $this->loginUser('Ivanov Tester', $guzzleClient);

        $sessionCookieValue = (new AddToCartApiHelper($guzzleClient))->addItemsToCart($itemsCountForAdd);
        WB::addCookieToDomain('session', $sessionCookieValue, Env::getInstance()->domain());
        return $this->openCheckoutPage();
    }

    public function openCheckoutPage(): Checkout
    {
        WB::open('/checkout');
        $checkoutPage = new Checkout();
        $checkoutPage->waitForReady();
        return $checkoutPage;
    }

    private function loginUser(string $userName = 'Tester User', Client $guzzleClient = null)
    {
        $createdUser = new RegisteredUser($userName, $guzzleClient);
        $userEmail = $createdUser->getCreatedUserEmail();
        $userPassword = UserDataGenerator::PASSWORD;

        $mainPage = (new MainPageOpener())->openMainPage();
        $mainPage->openAndGetMainMenuBlock();
        $authPopup = $mainPage->getMainMenu()->getUserMenu()->clickUserAuthButtonAndGetAuthPopupBlock();
        $authPopup->getAuthorizationForm()->login($userName, $userEmail, $userPassword);
    }
}