<?php

/**
 * @description Блок кнопок "Применить и сбросить"
 */

namespace AT\Block\Catalog;

use AT\BaseBlock;
use AT\Block\Button;
use AcceptanceCore\Core\WB;
use AcceptanceCore\Core\Utils\Waiting;

class FilterButtons extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'filter-buttons-block';

    private function getApplyButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'apply-button'));
    }

    private function getResetButton(): Button
    {
        return new Button((string) $this->me()->childByClass('filter-reset-button'));
    }

    public function clickOnApplyButtonOnFilterBlock(Filter $filterBlock = null)
    {
        if (is_null($filterBlock)) {
            $filterBlock = new Filter();
        }
        WB::step("Клик по кнопке 'Применить'");
        $this->getApplyButton()->click();
        Waiting::waitForElementNotVisible($filterBlock, 5, "Не удалось дождаться невидимости блока фильтра");
    }

    public function clickOnApplyButtonOnCategoriesListBlock()
    {
        WB::step("Клик по кнопке 'Применить'");
        $this->getApplyButton()->click();
        Waiting::waitForElementNotVisible($this->me(), 5, "Не удалось дождаться невидимости блока кнопок 'Применить' и 'Сбросить'");
    }


        public function clickOnResetButton()
    {
        WB::step("Клик по кнопке 'Сбросить'");
        $this->getResetButton()->click();
    }
}