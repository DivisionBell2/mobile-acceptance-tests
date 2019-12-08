<?php
/**
 * @description Блок c чекбоксами фильтра
 */

namespace AT\Block\Catalog;

use AT\BaseBlock;
use AT\Block\Checkbox;
use AcceptanceCore\Core\WB;
use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\ATException;
use Core\Utils\Arrays;

class Filter extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'filter-checkbox-block';

    private $filterButtons;

    private $checkboxesArr = [];

    public function getFilterButtons(): FilterButtons
    {
        if (is_null($this->filterButtons)) {
            $this->filterButtons = new FilterButtons();
        }
        return $this->filterButtons;
    }

    public function getFilterCheckboxes(bool $useCache = true): array
    {
        if ($useCache && count($this->checkboxesArr) > 0) {
            return $this->checkboxesArr;
        }

        Waiting::waitForElementVisible($this->me());
        $filterCheckboxesArr = [];
        $filterCheckboxSelector = $this->me()->childByTag('label', true);
        $filterCheckboxSelectorCount = WB::selectorCount($filterCheckboxSelector);
        for ($i = 1; $i <= $filterCheckboxSelectorCount; $i++) {
            $filterCheckbox = $filterCheckboxSelector->nthOfType($i)->childByAttribute(self::TEST_ATTR_NAME, 'checkbox-label');
            if (WB::isElementExists($filterCheckbox)) {
                $filterCheckboxesArr[] = new Checkbox((string) $filterCheckbox);
            }
        }
        Arrays::checkForEmptyArray($filterCheckboxesArr, 'Чекбоксы отсутствуют в блоке');
        $this->checkboxesArr = $filterCheckboxesArr;
        return $filterCheckboxesArr;
    }

    public function selectCheckboxAndGetNameByIndex(int $checkboxIndex = 0): string
    {
        WB::step("Выбор чекбокса с индексом {$checkboxIndex} в блоке фильтра");
        /**
         * @var $checkboxes Checkbox[]
         */
        $checkboxes = $this->getFilterCheckboxes();
        if (isset($checkboxes[$checkboxIndex])) {
            $checkbox = $checkboxes[$checkboxIndex];
            $checkbox->click();
            return mb_strtolower($checkbox->getText());
        }
        throw new ATException("Не найден checkbox с индексом {$checkboxIndex}");
    }

    public function waitForReady()
    {
        Waiting::waitForElementVisible($this->me(), 20, 'Не удалось дождаться блока фильтра с чекбоксами');
    }
}
