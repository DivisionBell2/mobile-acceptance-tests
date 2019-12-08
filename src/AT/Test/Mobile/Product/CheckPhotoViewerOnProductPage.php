<?php
/**
 * @description Тест на просмотр изображений на карточке товара по нажатию на них
 */

namespace AT\Test\Mobile\Product;

use AT\BaseTest;
use AT\Helpers\Product\Openers\ProductPageOpener;

class CheckPhotoViewerOnProductPage extends BaseTest
{
    public function testCheckPhotoViewerOnProductPage()
    {
        $imageNumber = 1;
        $productPage = (new ProductPageOpener())->openProductPageByApi();
        $imageViewer = $productPage->getProductImagesBlock()->clickOnImageAndGoToImageViewer($imageNumber);

        $this->assertTrue(
            $imageViewer->isImageInViewerVisible(),
            "Фотография большого размера не отображается на экране"
        );

        $imageViewer->clickOnCloseButtonAndWaitBigImageClosed();
        $this->assertTrue(
            $productPage->getProductImagesBlock()->isImageVisible($imageNumber),
            "Отсутствует фотография в блоке изображений товара"
        );
    }
}