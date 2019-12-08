<?php
/**
* @description Блок кнопок сортировки и фильтров
*/

namespace AT\Block\Catalog;

use AT\BaseBlock;
use AT\Block\Button;
use AcceptanceCore\Core\WB;
use AcceptanceCore\Core\Utils\Waiting;

class SortButtons extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'sort-block';

    private function getFilterButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'filter-button'));
    }

    private function getSortButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'sort-by-button'));
    }

    public function clickOnFilterButton()
    {
        WB::step("Клик по кнопке 'Фильтры'");
        $this->getFilterButton()->click();
        Waiting::waitForElementNotVisible($this->me(), 5, "Блок сортировки и фильтров остался на странице");
    }

    public function clickOnSortButton(): Sort
    {
        WB::step("Клик по кнопке 'Сортировка'");
        $this->getSortButton()->click();
        //Блок Sort является частью страницы, поэтому необходимо также дождаться,
        //пока другие элементы будут закрыты им, иначе тест упадет
        Waiting::waitForElementNotVisible($this->me(), 5, "Блок сортировки и фильтров остался на странице");
        $sortBlock = new Sort();
        $sortBlock->waitForReady();
        return $sortBlock;
    }
}
