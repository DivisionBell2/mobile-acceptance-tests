<?php
/**
 * @description Открывает страницу с указаным товаром
 */

namespace AT\Helpers\Product\Openers;

use AcceptanceCore\Core\ATException;
use AT\Block\Catalog\ProductItem;
use AT\Helpers\Catalog\Openers\CatalogClothesPageOpener;
use AcceptanceCore\Core\WB;
use AT\Helpers\Gender\GenderType;
use AT\Page\Product\Product;
use Core\Utils\Arrays;

class ProductPageOpener
{
    public function openProductPage(
        int $productIndex = 1,
        string $genderType = GenderType::WOMEN
    ): Product {
        $clothesPageOpener = new CatalogClothesPageOpener();
        if ($genderType === GenderType::WOMEN) {
            $clothesPage = $clothesPageOpener->openCatalogWomenClothesPage();
        } else {
            $clothesPage = $clothesPageOpener->openCatalogMenClothesPage();
        }

        /**
         * @var $productItemsArr ProductItem[]
         */
        $productItemsArr = $clothesPage->getProductList()->getProductItems();
        $productIndexForArr = $productIndex - 1;
        $productItemLinkArr = [];

        foreach ($productItemsArr as $productItem) {
            $productItemLinkArr[] = $productItem->getLinkHref();
        }
        Arrays::checkForEmptyArray($productItemLinkArr, 'Не найдены ссылки товаров в каталоге');

        if (isset($productItemLinkArr[$productIndexForArr])) {
            WB::openURL($productItemLinkArr[$productIndexForArr]);
            $productItemPage = new Product();
            $productItemPage->waitForReady();
            return $productItemPage;
        } else {
            throw new ATException("$productIndex по счету товар не обнаружен на странице выдачи");
        }
    }

    public function openProductPageByApi(
        string $genderType = GenderType::WOMEN,
        $query = 'платье'
    ): Product {
        $productUrl = (new ProductPageApiHelper())->getProductUrlNameFromCatalogResponse($genderType, $query);
        WB::open($productUrl);
        $productItemPage = new Product();
        $productItemPage->waitForReady();
        return $productItemPage;
    }
}
