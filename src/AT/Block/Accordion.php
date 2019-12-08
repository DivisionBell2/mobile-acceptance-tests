<?php
/**
* @description блок аккордеона
*/

namespace AT\Block;

use AcceptanceCore\Core\Selector;
use AT\BaseBlock;
use AcceptanceCore\Core\WB;
use AT\Page\Offer\Offer;
use Core\Utils\Arrays;
use AcceptanceCore\Core\ATException;

class Accordion extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'accordion';

    private $contentBlocks = [];

    private function getContentSelectors(bool $useCache = true): array
    {
        if ($useCache && count($this->contentBlocks) > 0) {
            return $this->contentBlocks;
        }

        $contentBlocks = [];
        $contentBlocksSelector = $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'accordion-button');
        $contentBlocksSelectorCount = WB::selectorCount($contentBlocksSelector);
        for ($i = 1; $i <= $contentBlocksSelectorCount; $i++) {
            $selector = $contentBlocksSelector->nthOfType($i)->childByAttribute(self::TEST_ATTR_NAME, 'content-block');
            if (WB::isElementExists($selector)) {
                $contentBlocks[] = new Selector((string) $selector);
            }
        }
        Arrays::checkForEmptyArray($contentBlocks, 'Блоки текста за кнопками аккордеона отсутствуют');
        $this->contentBlocks = $contentBlocks;
        return $contentBlocks;
    }

    public function getAccordionButtons(): array
    {
        $accordionButtons = [];
        $accordionButtonSelector = $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'accordion-button');
        $accordionButtonsCount = WB::selectorCount($accordionButtonSelector);
        for ($i = 1; $i <= $accordionButtonsCount; $i++) {
            $selector = $accordionButtonSelector->nthOfType($i)->childByTag('div', true);
            if (WB::isElementExists($selector)) {
                $accordionButtons[$i] = new Button((string) $selector);
            }
        }
        Arrays::checkForEmptyArray($accordionButtons, 'Не найдены кнопки в аккордеоне');
        return $accordionButtons;
    }

    public function clickOnAccordionButtonByIndex(int $buttonNumber)
    {
        /**
        * @var $buttons Button[]
        */
        $buttons = $this->getAccordionButtons();
        if ($button = $buttons[$buttonNumber]) {
            WB::step("Клик по $buttonNumber кнопке");
            $button->click();
        } else {
            throw new ATException("Кнопка отсутствует в аккордеоне");
        }
    }

    public function clickOnAccordionButton(int $buttonNumber)
    {
        /**
         * @var $buttons Button[]
         */
        $buttons = $this->getAccordionButtons();
        if ($button = $buttons[$buttonNumber]) {
            WB::step("Клик по {$buttonNumber} кнопке");
            $button->click();
            $offerPage = new Offer();
            $offerPage->waitForReady();
            return;
        }
        throw new ATException("Кнопка отсутствует в аккордеоне");
    }

    public function isContentSelectorVisible(int $blockNumber): bool
    {
        WB::step("Проверка появления блока с контентом под кнопкой {$blockNumber}");
        $blockNumber--;
        if ($contentSelector = $this->getContentSelectors()[$blockNumber]) {
            return WB::isElementVisible($contentSelector);
        }
        throw new ATException("Блок контента не найден");
    }

    public function isContentSelectorNotVisible(int $blockNumber): bool
    {
        return !$this->isContentSelectorVisible($blockNumber);
    }
}