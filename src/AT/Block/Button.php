<?php
/**
* @description Описание стандартной кнопки
*/

namespace AT\Block;

use AcceptanceCore\Core\Selector;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\BasePage;

class Button extends BaseBlock
{
    public function click()
    {
        WB::click($this->me());
    }

    public function doubleClick()
    {
        WB::doubleClick($this->me());
    }

    public function clickHTML()
    {
        WB::clickHTML($this->me());
    }

    public function getName(): string
    {
        return WB::grabTextFrom($this->me());
    }

    public function isEnabled(bool $directTagButton = false): bool
    {
        return !$this->isDisabled($directTagButton);
    }

    public function isDisabled(bool $directTagButton = false): bool
    {
        if ($directTagButton) {
            return WB::isDisabled($this->me());
        } else {
            return WB::isDisabled($this->_getInnerButtonSelector());
        }
    }

    private function _getInnerButtonSelector(): Selector
    {
        return $this->me()->childByTag('button', true);
    }

    public function clickAndGoToPageInCurrentWindow(BasePage $page): BasePage
    {
        $this->click();
        $page->waitForReady();
        return $page;
    }
}