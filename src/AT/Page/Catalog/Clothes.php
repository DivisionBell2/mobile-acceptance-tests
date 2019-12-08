<?php
/**
* @description Страница каталога "Одежда"
*/

namespace AT\Page\Catalog;

use AcceptanceCore\Core\Utils\Waiting;
use AT\BasePage;
use AT\Block\Catalog\CatalogHeader;
use AT\Block\Catalog\ProductList;
use AT\Block\Catalog\FilterCategoriesList;
use AT\Block\Catalog\SortButtons;
use AT\Page\iPageTemplate;

class Clothes extends BasePage implements iPageTemplate
{
    private $filterCategoriesList;
    private $productList;
    private $catalogHeader;

    public function getFilterCategoriesList(): FilterCategoriesList
    {
        if (is_null($this->filterCategoriesList)) {
            $this->filterCategoriesList = new FilterCategoriesList();
        }
        return $this->filterCategoriesList;
    }

    public function getProductList(): ProductList
    {
        if (is_null($this->productList)) {
            $this->productList = new ProductList();
        }
        return $this->productList;
    }

    public function getCatalogHeader(): CatalogHeader
    {
        if (is_null($this->catalogHeader)) {
            $this->catalogHeader = new CatalogHeader();
        }
        return $this->catalogHeader;
    }

    public function waitForReady()
    {
        parent::waitForReady();
        Waiting::waitForElementVisible(
            $this->getCatalogHeader()->me(),
            10,
            'Не удалось дождаться видимости хедера каталога'
        );
    }
}