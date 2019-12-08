<?php
/**
 * @description Страница каталога "Обувь"
 */

namespace AT\Page\Catalog;

use AcceptanceCore\Core\Utils\Waiting;
use AT\BasePage;
use AT\Block\Catalog\ProductList;
use AT\Page\iPageTemplate;

class Shoes extends BasePage implements iPageTemplate
{
    private $productList;

    public function getProductList(): ProductList
    {
        if (is_null($this->productList)) {
            $this->productList = new ProductList();
        }
        return $this->productList;
    }

    public function waitForReady()
    {
        parent::waitForReady();
        Waiting::waitForElementVisible(
            $this->getProductList()->me(),
            10,
            'Не удалось дождаться видимости товаров на странице'
        );
    }
}