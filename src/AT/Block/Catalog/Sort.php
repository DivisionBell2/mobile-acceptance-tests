<?php
/**
 * @description Блок кнопок сортировки
 */

namespace AT\Block\Catalog;

use AcceptanceCore\Core\ATException;
use AcceptanceCore\Core\Selector;
use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\Button;
use AT\Helpers\Catalog\SortByNames;
use AT\Page\Catalog\Clothes;
use Core\Utils\Arrays;

class Sort extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'sort-container';

    private $sortButtons = [];

    private function getSortButtonsSelector(): Selector
    {
        return $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'sort-item');
    }

    private function getSortButtons(bool $useCache = true): array
    {
        if ($useCache && count($this->sortButtons) > 0) {
            return $this->sortButtons;
        }

        $sortButtons = [];
        $sortButtonsSelector = $this->getSortButtonsSelector();
        $sortButtonsSelectorCount = WB::selectorCount($sortButtonsSelector);
        for ($i = 1; $i <= $sortButtonsSelectorCount; $i++) {
            $selector = $sortButtonsSelector->nthOfType($i);
            if (WB::isElementExists($selector)) {
                $sortButtonName = mb_strtolower(WB::grabTextFrom($selector));
                $sortButtons[$sortButtonName] = new Button((string) $selector);
            }
        }
        Arrays::checkForEmptyArray($sortButtons, 'Кнопки сортировки отсутствуют в меню');
        $this->sortButtons = $sortButtons;
        return $sortButtons;
    }

    public function clickOnSortButton(string $sortBy = SortByNames::SORT_ASCENDING_PRICE): Clothes
    {
        $sortButtons = $this->getSortButtons();
        WB::step("Клик по кнопке {$sortBy} и возврат в каталог");

        /**
         * @var $Sort Button[]
         */
        if (isset($sortButtons[mb_strtolower($sortBy)])) {
            $sortButtons[mb_strtolower($sortBy)]->click();
            Waiting::waitForElementNotVisible($this->me(), 3, "Окно сортировки отображается на экране");
            $closePage = new Clothes();
            $closePage->waitForReady();
            return $closePage;
        }
        throw new ATException("Отсутствует кнопка с названием {$sortBy}");
    }

    public function waitForReady()
    {
        Waiting::waitForLoad();
        //Здесь больше не к чему привязаться, только кнопки. Поэтому берется первая кнопка из доступных.
        Waiting::waitForElementVisible(
            $sortButtonsSelector = $this->getSortButtonsSelector(),
            3,
            'Не удалось дождаться кнопок сортировки'
        );
    }
}