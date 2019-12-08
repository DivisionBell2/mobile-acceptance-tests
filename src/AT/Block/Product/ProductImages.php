<?php
/**
 * @description Блок изображений на странице товара
 */

namespace AT\Block\Product;

use AcceptanceCore\Core\ATException;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\Image;
use Core\Utils\Arrays;

class ProductImages extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'mobile-product-image';

    private $images = [];

    private function getImages(bool $useCache = true): array
    {
        if ($useCache && count($this->images) > 0) {
            return $this->images;
        }

        $images = [];
        ///Здесь блоки импортируются из стороннего пакета ReactSiema, к которому нет доступа в верстке
        $imageSelector = $this->me()->childByTag('div', true)->childByTag('div', true)->childByTag('div', true)->childByTag('div', true);
        $imageSelectorCount = WB::selectorCount($imageSelector);
        for ($i = 1; $i <= $imageSelectorCount; $i++) {
            $image = $imageSelector->nthOfType($i)->childByAttribute(self::TEST_ATTR_NAME, 'image');
            if (WB::isElementExists($image)) {
                $images[] = new Image((string) $image);
            }
        }
        Arrays::checkForEmptyArray($images, 'Изображения отсутствуют в блоке');
        $this->images = $images;
        return $images;
    }

    public function clickOnImageAndGoToImageViewer(int $imageNumber = 1): ImageViewer
    {
        WB::step("Клик по изображению с индексом {$imageNumber} в блоке изображений товара");
        $imageNumber--;
        $images = $this->getImages();
        WB::click($images[$imageNumber]);
        $imageViewer = new ImageViewer();
        $imageViewer->waitForReady();
        return $imageViewer;
    }

    public function isImageVisible(int $imageNumber = 1): bool
    {
        WB::step("Проверка отображения фотографии с индексом {$imageNumber} в блоке изображений товара");
        $imageNumber--;
        $images = $this->getImages();
        if (isset($images[$imageNumber]) && WB::isElementVisible($images[$imageNumber])) {
            return true;
        }
        throw new ATException("Изображение в блоке отсутствует");
    }
}