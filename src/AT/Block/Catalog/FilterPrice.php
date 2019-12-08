<?php
/**
 * @description Блок c фильтром по цене
 */

namespace AT\Block\Catalog;

use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\Input;

class FilterPrice extends Filter
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'filter-price-block';

    private $minValueInput;
    private $maxValueInput;

    private function getMinValueInput(): Input
    {
        if (is_null($this->minValueInput)) {
            $this->minValueInput = new Input((string) $this->me()->childByAttribute('data-test', 'min-value'));
        }
        return $this->minValueInput;
    }

    private function getMaxValueInput(): Input
    {
        if (is_null($this->maxValueInput)) {
            $this->maxValueInput = new Input((string) $this->me()->childByAttribute('data-test', 'max-value'));
        }
        return $this->maxValueInput;
    }

    public function setFilterPriceValues(?string $minValue = '2000', ?string $maxValue = '10000')
    {
        WB::step("Ввод {$maxValue} в поле 'До'");
        $this->getMaxValueInput()->clickAndInput($maxValue);
        WB::step("Ввод {$minValue} в поле 'От'");
        $this->getMinValueInput()->clickAndInput($minValue);
    }
}
