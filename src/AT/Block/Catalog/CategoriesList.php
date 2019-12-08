<?php
/**
 * @description Блок категорий в каталоге
 */

namespace AT\Block\Catalog;

use AcceptanceCore\Core\ATException;
use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\BasePage;
use AT\Block\Link;
use Core\Utils\Arrays;

class CategoriesList extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'multilevel-list';

    private $categoriesLinks = [];

    public function getCategories(bool $useCache = true): array
    {
        if ($useCache && count($this->categoriesLinks) > 0) {
            return $this->categoriesLinks;
        }

        $categoriesLinks = [];
        $categoriesSelector = $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'category-item');
        $categoriesSelectorCount = WB::selectorCount($categoriesSelector);
        for ($i = 1; $i <= $categoriesSelectorCount; $i++) {
            $selector = $categoriesSelector->nthOfType($i)->childByTag('a', true);
            if (WB::isElementExists($selector)) {
                $categoryName = mb_strtolower(WB::grabTextFrom($selector));
                $categoriesLinks[$categoryName] = new Link((string) $selector);
            }
        }
        Arrays::checkForEmptyArray($categoriesLinks, 'Категории товаров отсутствуют в списке');
        $this->categoriesLinks = $categoriesLinks;
        return $categoriesLinks;
    }

    public function clickOnCategoryAndGoToCatalog(BasePage $page, string $categoryName): BasePage
    {
        WB::step("Клик по категории {$categoryName} и переход на страницу каталога");
        $categoryName = mb_strtolower($categoryName);
        /**
         * @var $CategoriesList Link[]
         */
        $categoryLinks = $this->getCategories();
        if (isset($categoryLinks[$categoryName])) {
            $categoryLinks[$categoryName]->clickAndGoToPageInCurrentWindow($page);
            Waiting::waitForElementNotVisible($this->me(), 5, "Блок категорий каталога не закрылся");
            $page->waitForReady();
            return $page;
        } else {
            throw new ATException("Категория {$categoryName} отсутствует в списке категорий");
        }
    }
}