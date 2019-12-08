<?php
/**
 * @description Просмотрщик фотографий большого формата
 */

namespace AT\Block\Product;

use AcceptanceCore\Core\Selector;
use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\Button;

class ImageViewer extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'mobile-image-viewer';

    private function getBigImage(): Selector
    {
        return $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'mobile-big-image');
    }

    private function getCloseButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'close-button'));
    }

    public function isImageInViewerVisible(): bool
    {
        WB::step("Проверка на то, что на экране отображается фотография большого размера");
        return WB::isElementVisible($this->getBigImage(), "На экране не отображается фотография большого размера");
    }

    public function clickOnCloseButtonAndWaitBigImageClosed()
    {
        WB::step("Клик по кнопке 'Закрыть' и ожидание закрытия фотографии большого размера");
        $this->getCloseButton()->click();
        Waiting::waitForElementNotVisible($this->getBigImage(), 3, "Фотография большого размера осталась на месте");
    }

    public function waitForReady()
    {
        Waiting::waitForLoad();
        Waiting::waitForElementVisible(
            $this->getBigImage(),
            3,
            'Не удалось дождаться видимости фотографии большого размера на странице'
        );
    }
}