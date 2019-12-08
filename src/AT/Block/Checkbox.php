<?php
/**
 * @description Описание стандартного блока чекбокса
 */

namespace AT\Block;

use AcceptanceCore\Core\WB;
use AT\BaseBlock;

class Checkbox extends BaseBlock
{
    public function isChecked()
    {
        return WB::isSelected($this->me());
    }

    public function isDisabled()
    {
        return WB::isDisabled($this->me());
    }

    public function click()
    {
        WB::click($this->me());
    }

    public function getText(): string
    {
        return WB::grabTextFrom($this->me());
    }

    public function check(bool $htmlClick = false)
    {
        if (!$this->isChecked()) {
            $htmlClick ? $this->clickHTML() : $this->click();
        }
    }

    public function unCheck(bool $htmlClick = false)
    {
        if ($this->isChecked()) {
            $htmlClick ? $this->clickHTML() : $this->click();
        }
    }
}
