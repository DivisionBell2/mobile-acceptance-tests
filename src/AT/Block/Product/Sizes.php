<?php
/**
 * @description Блок размеров на странице товара
 */

namespace AT\Block\Product;

use AcceptanceCore\Core\ATException;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use Core\Utils\Arrays;

class Sizes extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'sizes-block';

    private $sizesButtonsArr = [];

    public function getAvaliableSizeButtonBlock(int $sizeButtonNumber = 1): SizeButton
    {
        $sizeButtonNumber--;
        $sizeButtonsArr = $this->getAvailableSizeButtons();
        if (isset($sizeButtonsArr[$sizeButtonNumber])) {
            return $sizeButtonsArr[$sizeButtonNumber];
        } else {
            throw new ATException("Не найдена кнопка размера номер {$sizeButtonNumber}");
        }
    }

    public function getAvailableSizeButtons(bool $useCache = true): array
    {
        if ($useCache && count($this->sizesButtonsArr) > 0) {
            return $this->sizesButtonsArr;
        }
        $sizesButtonsArr = [];
        $sizesButtonsSelector = $this->me()->childByTag('div', true);
        $sizesButtonsSelectorsCount = WB::selectorCount($sizesButtonsSelector);
        for ($i = 1; $i <= $sizesButtonsSelectorsCount; $i++) {
            $sizeButtonSelector = $sizesButtonsSelector->nthOfType($i);
            if (WB::isElementExists($sizeButtonSelector->notHavingAttributeContains(
                self::TEST_ATTR_NAME,
                'size-disabled'
            ))) {
                $sizesButtonsArr[] = new SizeButton((string) $sizeButtonSelector);
            }
        }
        Arrays::checkForEmptyArray($sizesButtonsArr, 'Нет доступных размеров у товара');
        $this->sizesButtonsArr = $sizesButtonsArr;
        return $sizesButtonsArr;
    }

    public function clickAvaliableSizeButton(int $sizeButtonNumber = 1)
    {
        WB::step("Клик по кнопке размера номер {$sizeButtonNumber}");
        $this->getAvaliableSizeButtonBlock($sizeButtonNumber)->click();
    }
}