<?php
/**
 * @description Класс, создающий запрос в API для открытия карточки товара
 */

namespace AT\Helpers\Product\Openers;

use AcceptanceCore\Core\ATException;
use AcceptanceCore\Core\Env;
use AcceptanceCore\Core\WB;
use AcceptanceCore\Logger;
use AT\Helpers\Gender\GenderType;
use Core\Utils\Arrays;
use GuzzleHttp\Client;

class ProductPageApiHelper
{
    public function getProductUrlNameFromCatalogResponse(
        string $genderType = GenderType::WOMEN,
        string $query = 'платье'
    ): string {
        $catalogUrl = Env::getInstance()->url() . '/api/v4/catalog';

        $client = new Client([
            'defaults' => ['verify' => false],
        ]);

        WB::step(
            "Делаем запрос списка продуктов с URL {$catalogUrl}"
        );

        switch ($genderType) {
            case GenderType::WOMEN:
                $category = 'zhenshchinam';
                break;
            case GenderType::MEN:
                $category = 'muzhchinam';
                break;
            default:
                $category = 'zhenshchinam';
        }

        $response = $client->get($catalogUrl, [
            'query' => [
                'type' => 'catalog',
                'category' => $category,
                'query' => $query
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
        } else {
            throw new ATException("Запрос списка продуктов по URL {$catalogUrl} не содержит products");
        }
        foreach ($products as $keyProduct => $product) {
            if (count($product['product_variations']) > 0) {
                if (isset($product['url_name'])) {
                    $productUrl = '/' . $product['url_name'];
                    WB::step('Найден URL продукта: ' . $productUrl);
                    return $productUrl;
                }
                continue;
            }
            Logger::log("У товара {$keyProduct} нет product_variations, переходим к следующему");
        }
        throw new ATException("В массиве products не найдено необходимого продукта с product_variations");
    }
}