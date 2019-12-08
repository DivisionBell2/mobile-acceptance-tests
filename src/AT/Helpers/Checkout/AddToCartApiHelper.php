<?php
/**
 * @description Класс, создающий запрос в API для добавления товара в корзину
 */

namespace AT\Helpers\Checkout;

use AcceptanceCore\Core\ATException;
use AcceptanceCore\Core\Env;
use AcceptanceCore\Core\WB;
use AcceptanceCore\Logger;
use Core\Utils\Arrays;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class AddToCartApiHelper
{
    private $guzzleClient;
    private $products = [];
    private $usedProductVariationIds = [];

    public function __construct(Client $guzzleClient = null)
    {
        if ($guzzleClient) {
            $this->guzzleClient = $guzzleClient;
        } else {
            $this->guzzleClient = new Client([
                'defaults' => ['verify' => false],
                'cookies' => true
            ]);
        }
    }

    /**
     * @description Возвращает значение cookie сессии, которое можно сохранить в браузер
     */
    public function addItemsToCart(int $itemsCountForAdd = 1): string
    {
        $sessionCookieValue = '';
        for ($i = 1; $i <= $itemsCountForAdd; $i++) {
            $sessionCookieValue = $this->makeAddToCartRequest($i);
        }
        if ($sessionCookieValue) {
            return $sessionCookieValue;
        }
        throw new ATException("Cookie 'session' не может быть пустой");
    }

    private function makeAddToCartRequest(int $productIndex = 1): string
    {
        $productVariationId = $this->getProductVariationIdFromCatalogResponse($productIndex);

        $requestArr = ['product_variation_id' => $productVariationId];
        $requestJson = json_encode($requestArr);

        $cartItemsUrl = Env::getInstance()->url() . '/api/v2/cart/items';

        WB::step("Делаем запрос добавления в корзину с URL {$cartItemsUrl} и product_variation_id {$productVariationId}");
        $response = $this->guzzleClient->post($cartItemsUrl, [
            'body' => $requestJson
        ]);

        $bodyResponse = $response->getBody()->getContents();
        $resultFromResponse = json_decode($bodyResponse, true);

        if (isset($resultFromResponse['items']) && count($resultFromResponse['items']) > 0) {
            WB::step("Запрос добавления в корзину с URL {$cartItemsUrl} " .
                "прошел успешно с product_variation_id {$productVariationId}");
        } else {
            throw new ATException("Запрос добавления в корзину c URL {$cartItemsUrl} " .
                "с product_variation_id {$productVariationId} НЕ прошел успешно");
        }

        /**
         * @var $cookieJar CookieJar
         */
        $cookieJar = $this->guzzleClient->getConfig('cookies');
        $sessionCookie = $cookieJar->getCookieByName('session');
        return $sessionCookie->getValue();
    }

    private function getProductVariationIdFromCatalogResponse(int $productIndex = 1): int
    {
        WB::step("Запрошен product c порядковым номером {$productIndex} из выдачи catalog");
        // Не используем кэширование products из-за того, что для каждого добавления товара в корзину разумно делать запрос с latest-остатками
        $this->products = $this->getProducts();

        $startProductIndex = $productIndex - 1;
        for ($i = $startProductIndex; $i < count($this->products); $i++) {
            if (isset($this->products[$i])) {
                $productVariations = $this->products[$i]['product_variations'];
                $productUrl = $this->products[$i]['url_name'];

                if (count($productVariations) > 0) {
                    foreach ($productVariations as $productVariation) {
                        $productVariationId = $productVariation['id'];

                        if (in_array($productVariationId, $this->usedProductVariationIds)) {
                            Logger::info("Под критерии НЕ подошел product c url {$productUrl} " .
                                "и product_variations => id {$productVariationId}, потому что он использовался ранее. " .
                                "Список используемых IDs: " . var_export($this->usedProductVariationIds, true));
                            continue;
                        }

                        if ($productVariationQuantity = $productVariation['size']['quantity']) {
                            if ($productVariationQuantity > 1) {
                                Logger::info("Под критерии подошел product c url {$productUrl} " .
                                    "и product_variations => id {$productVariationId} и кол-вом {$productVariationQuantity}");
                                $this->usedProductVariationIds[] = $productVariationId;
                                return $productVariationId;
                            }
                            Logger::info("Под критерии НЕ подошел product c url {$productUrl} " .
                                "и product_variations => id {$productVariationId} и кол-вом {$productVariationQuantity}");
                            continue;
                        }
                    }
                }
                Logger::info("У product с index {$i} и URL {$productUrl} не найдено подходящих product_variations");
            } else {
                throw new ATException("В массиве products не найдено необходимого индекса {$startProductIndex} product");
            }
        }
        throw new ATException("В массиве products со стартовым index {$startProductIndex} нет подходящих product_variations => id");
    }

    private function getProducts(): array
    {
        $catalogUrl = Env::getInstance()->url() . '/api/v4/catalog';

        WB::step("Делаем запрос списка products с URL {$catalogUrl}");

        $response = $this->guzzleClient->get($catalogUrl, [
            'query' => [
                'type' => 'catalog',
                'category' => 'zhenshchinam',
                'query' => 'одежда' // TODO Нужно потом поменять логику, пока так из-за проданных товаров в выдаче
            ],
        ]);

        $bodyResponse = $response->getBody()->getContents();
        $resultArr = json_decode($bodyResponse, true);

        if (isset($resultArr['products'])) {
            $products = $resultArr['products'];
            Arrays::checkForEmptyArray(
                $products,
                "Получены пустые products в ответе от API {$catalogUrl} списка продуктов"
            );
            return $products;
        } else {
            throw new ATException("Запрос списка products по URL {$catalogUrl} не содержит products");
        }
    }
}