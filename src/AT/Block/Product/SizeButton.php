<?php
/**
 * @description Блок кнопки с размером
 */

namespace AT\Block\Product;

use AcceptanceCore\Core\Selector;
use AcceptanceCore\Core\WB;
use AT\Block\Button;

class SizeButton extends Button
{
    private function getSizeValueSelector(): Selector
    {
        return $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'current-size');
    }

    public function getSizeValue(): string
    {
        return mb_strtolower(WB::grabTextFrom($this->getSizeValueSelector()));
    }

}