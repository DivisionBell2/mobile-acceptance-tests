<?php
/**
 * @description Блок фильтров
 */

namespace AT\Block\Catalog;

use AT\BaseBlock;
use AcceptanceCore\Core\WB;
use AcceptanceCore\Core\ATException;
use AT\Block\Link;
use Core\Utils\Arrays;

class FilterCategoriesList extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'filter-popup-block';

    private $filterButtons;
    private $filterSections = [];

    public function getFilterButtons(): FilterButtons
    {
        if (is_null($this->filterButtons)) {
            $this->filterButtons = new FilterButtons();
        }
        return $this->filterButtons;
    }

    /**
     * @return array Link[]
     * @throws ATException
     */
    public function getFilterSectionsLinks(bool $useCache = true): array
    {
        if ($useCache && count($this->filterSections) > 0) {
            return $this->filterSections;
        }

        $filterSections = [];

        $filtersSectionSelector = $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'filter-categories')->childByTag('a', true);
        $filtersSectionSelectorsCount = WB::selectorCount($filtersSectionSelector);

        for ($i = 1; $i <= $filtersSectionSelectorsCount; $i++) {
            $filtersSectionLinkSelector = $filtersSectionSelector->nthOfType($i)->childByAttribute(self::TEST_ATTR_NAME, 'filter-name');
            if (WB::isElementExists($filtersSectionLinkSelector)) {
                $filtersSectionName = mb_strtolower(WB::grabTextFrom($filtersSectionLinkSelector));
                $filterSections[$filtersSectionName] = new Link((string) $filtersSectionLinkSelector);
            }
        }
        Arrays::checkForEmptyArray($filterSections, 'Не найдены ссылки разделов фильтров');
        $this->filterSections = $filterSections;
        return $filterSections;
    }

    public function selectAndGetFilterSectionByName(string $filterName): Filter
    {
        $filterNameLowerCase = mb_strtolower($filterName);

        /**
         * @var $filterSections Link[]
         */
        $filterSections = $this->getFilterSectionsLinks();

        if (array_key_exists($filterNameLowerCase, $filterSections)) {
            $filterSection = $filterSections[$filterNameLowerCase];
            WB::step("Клик по фильтру '{$filterNameLowerCase}'");
            $filterSection->click();

            switch ($filterNameLowerCase) {
                case 'цена':
                    $filterBlock = new FilterPrice();
                    break;
                case 'размер':
                    $filterBlock = new FilterSize();
                    break;
                default:
                    $filterBlock = new Filter();
            }
            $filterBlock->waitForReady();
            return $filterBlock;
        } else {
            throw new ATException("Нет раздела фильтров {$filterNameLowerCase}");
        }
    }
}