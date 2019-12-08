<?php
/**
 * @description Блок c фильтром по размеру
 */

namespace AT\Block\Catalog;

use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\Block\Checkbox;
use Core\Utils\Arrays;

class FilterSize extends Filter
{
    private $checkboxesArr = [];

    public function getFilterCheckboxes(bool $useCache = true): array
    {
        if ($useCache && count($this->checkboxesArr) > 0) {
            return $this->checkboxesArr;
        }

        Waiting::waitForElementVisible($this->me());
        $filterCheckboxesArr = [];
        $filterCheckboxSelector = $this->me()->childByTag('tr', true)->nthOfType(2)->childByTag('td', true);;
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
}
