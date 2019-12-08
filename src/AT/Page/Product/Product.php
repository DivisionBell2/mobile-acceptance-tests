<?php
/**
 * @description Страница товара
 */

namespace AT\Page\Product;

use AcceptanceCore\Core\Utils\Waiting;
use AT\BasePage;
use AT\Block\Product\AddToUserProfile;
use AT\Block\Product\ProductImages;
use AT\Block\Product\ProductInfo;
use AT\Block\Product\Sizes;
use AT\Block\Product\SizesAndInfo;
use AT\Page\iPageTemplate;

class Product extends BasePage implements iPageTemplate
{
    private $baseProductInfo;
    private $productImages;
    private $productSizes;
    private $addToUserProfile;
    private $sizeAndInfo;

    public function getBaseProductInfoBlock(): ProductInfo
    {
        if (is_null($this->baseProductInfo)) {
            $this->baseProductInfo = new ProductInfo();
        }
        return $this->baseProductInfo;
    }

    public function getProductImagesBlock(): ProductImages
    {
        if (is_null($this->productImages)) {
            $this->productImages = new ProductImages();
        }
        return $this->productImages;
    }

    public function getProductSizesBlock(): Sizes
    {
        if (is_null($this->productSizes)) {
            $this->productSizes = new Sizes();
        }
        return $this->productSizes;
    }

    public function getAddToUserProfileBlock(): AddToUserProfile
    {
        if (is_null($this->addToUserProfile)) {
            $this->addToUserProfile = new AddToUserProfile();
        }
        return $this->addToUserProfile;
    }

    public function getSizeAndInfoBlock(): SizesAndInfo
    {
        if (is_null($this->sizeAndInfo)) {
            $this->sizeAndInfo = new SizesAndInfo();
        }
        return $this->sizeAndInfo;
    }

    public function waitForReady()
    {
        parent::waitForReady();
        Waiting::waitForElementVisible(
            $this->getBaseProductInfoBlock()->me(),
            10,
            'Не удалось дождаться видимости блока основной информации о продукте'
        );
    }
}