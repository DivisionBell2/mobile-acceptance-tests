<?php
/**
* @description Стандартный блок ввода текста
*/

namespace AT\Block;

use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;

class Input extends BaseBlock
{
    public function focus(?bool $waitForAjax = true)
    {
        WB::focus($this->me());
        if ($waitForAjax) {
            Waiting::waitForAjax();
        }
    }

    public function click(?bool $waitForAjax = false)
    {
        WB::click($this->me());
        if ($waitForAjax) {
            Waiting::waitForAjax();
        }
    }

    public function input(string $text, ?bool $forceEmptyVal = false, ?bool $hideValueInLog = false, ?bool $htmlClick = false)
    {
        $this->_inputTextToField($text, $forceEmptyVal, $htmlClick, $hideValueInLog);
    }

    public function inputWithJsClick(string $text, ?bool $forceEmptyVal = false, ?bool $jsClick = true)
    {
        $this->_inputTextToField($text, $forceEmptyVal, $jsClick);
    }

    public function inputWithHtmlClick(string $text)
    {
        Waiting::waitForElementVisible($this->me(), 30);
        WB::fillFieldWithHtmlClick($this->me(), $text);
    }

    public function focusAndInput(string $text, ?bool $forceEmptyVal = false)
    {
        $this->focus();
        $this->input($text, $forceEmptyVal);
    }

    public function clickAndInput(string $text, ?bool $forceEmptyVal = false)
    {
        $this->click(true);
        $this->input($text, $forceEmptyVal);
    }

    public function getMask(): string
    {
        return WB::executeJs("return $('{$this->me()}').inputmask('getemptymask').join('')");
    }

    public function getCursorPosition(): int
    {
        return WB::getCursorPosition($this->me());
    }

    public function getText(): string
    {
        return WB::grabValueFrom($this->me());
    }

    public function clearDataInput()
    {
        WB::clearField($this->me());
        Waiting::waitForAjax();
    }

    public function clearDataMaskInput()
    {
        WB::setEmptyValue($this->me());
    }

    public function clearWithKeyboard()
    {
        WB::clearWithKeyboard($this->me());
    }

    public function isDisabled()
    {
        return WB::isDisabled($this->me());
    }

    /**
     * @return bool True if the field is highlighted, false otherwise.
     */
    public function isErrorHighlighted(): bool
    {
        return WB::isHasClass($this->me(), 'ko-validation-error');
    }

    public function getPlaceHolder(): string
    {
        return WB::getAttribute($this->me(), 'placeholder');
    }

    public function getInputType(): string
    {
        return WB::getAttribute($this->me(), 'type');
    }

    private function _inputTextToField(
        string $text,
        ?bool $forceEmptyVal = false,
        ?bool $jsClick = false,
        ?bool $hideValueInLog = false
    ) {
        Waiting::waitForElementVisible($this->me(), 30);
        if ($forceEmptyVal) {
            $this->clearDataInput();
        }
        WB::fillField($this->me(), $text, $jsClick, $hideValueInLog);
    }
}