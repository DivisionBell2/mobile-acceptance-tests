<?php
/**
 * @description Блок, отвечающий за методы автозаполнения
 */

namespace AT\Block;

use AcceptanceCore\Core\Selector;
use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use Core\Utils\Arrays;

class AutoComplete extends BaseBlock
{
    private function getInputSelector(): Selector
    {
        return $this->me()->childByTag('input');
    }

    private function getSuggestSelector(): Selector
    {
        return $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'suggesterSelector');
    }

    /**
     * @return array Link[]
     * @throws \AcceptanceCore\Core\ATException
     */
    private function getSuggests(): array
    {
        $suggestsArr = [];
        $suggestSelectorsCount = WB::selectorCount($this->getSuggestSelector());
        for ($i = 1; $i <= $suggestSelectorsCount; $i++) {
            $suggestSelector = $this->getSuggestSelector()->nthOfType($i);
            if (WB::isElementExists($suggestSelector)) {
                $suggestsArr[] = new Link((string) $suggestSelector);
            }
        }
        Arrays::checkForEmptyArray($suggestsArr, 'Не найдены ссылки в саджесте');
        return $suggestsArr;
    }

    public function inputTextAndSubmitSuggest(string $text)
    {
        (new Input((string) $this->getInputSelector()))->input($text);
        Waiting::waitForAjax();
        /**
         * @var $suggest Link
         */
        $suggest = $this->getSuggests()[0];
        $suggest->click();
        Waiting::waitForLoad();
    }

}