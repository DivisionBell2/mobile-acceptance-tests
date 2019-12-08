<?php
/**
 * @description Блок категорий в главном меню
 */

namespace AT\Block\HeaderMenu;

use AcceptanceCore\Core\ATException;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\BasePage;
use AT\Block\Link;
use Core\Utils\Arrays;

class CategoriesList extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'mobile-categories-list';

    private $categoriesLinks = [];
    private $subcategoriesLinks = [];

    private function getCategoriesLinks(bool $useCache = true): array
    {
        if ($useCache && count($this->categoriesLinks) > 0) {
            return $this->categoriesLinks;
        }

        $categoriesLinks = [];
        $categoriesLinksSelector = $this->me()->childByTag('div', 'true')
            ->childByAttribute(self::TEST_ATTR_NAME, 'main-menu');
        $categoriesLinksSelectorCount = WB::selectorCount($categoriesLinksSelector);
        for ($i = 1; $i <= $categoriesLinksSelectorCount; $i++) {
            $selector = $categoriesLinksSelector->nthOfType($i);
            if (WB::isElementExists($selector)) {
                $categoryName = mb_strtolower(WB::grabTextFrom($selector));
                $categoriesLinks[$categoryName] = new Link((string) $selector);
            }
        }
        Arrays::checkForEmptyArray($categoriesLinks, 'Категории товаров отсутствуют в меню');
        $this->categoriesLinks = $categoriesLinks;
        return $categoriesLinks;
    }

    private function getSubcategoriesLinks(bool $useCache = true): array
    {
        if ($useCache && count($this->subcategoriesLinks) > 0) {
            return $this->subcategoriesLinks;
        }

        $subcategoriesLinks = [];
        $subcategoriesLinksSelector = $this->me()->childByTag('div', 'true')
            ->childByAttribute(self::TEST_ATTR_NAME, 'mobile-submenu');
        $subcategoriesLinksSelectorCount = WB::selectorCount($subcategoriesLinksSelector);
        for ($i = 1; $i <= $subcategoriesLinksSelectorCount; $i++) {
            $selector = $subcategoriesLinksSelector->nthOfType($i);
            if (WB::isElementExists($selector)) {
                $subcategoriesLinks[] = new Link((string) $selector);
            }
        }
        Arrays::checkForEmptyArray($subcategoriesLinks, 'Категории товаров отсутствуют в меню');
        $this->subcategoriesLinks = $subcategoriesLinks;
        return $subcategoriesLinks;
    }

    public function clickOnCategoryName(string $categoryName)
    {
        WB::step("Клик по категории {$categoryName} в главном меню");
        $categoryNameLowerCase = mb_strtolower($categoryName);

        /**
         * @var $CategoriesList Link[]
         */
        $categoriesLinks = $this->getCategoriesLinks();
        if (isset($categoriesLinks[$categoryNameLowerCase])) {
            $categoriesLinks[$categoryNameLowerCase]->click();
        } else {
            throw new ATException("Категория {$categoryName} отсутствует в главном меню");
        }
    }

    public function clickOnSubcategoryAndGoToCatalogPage(BasePage $page, int $subcategoryNumber = 1): BasePage
    {
        WB::step("Клик по подкатегории {$subcategoryNumber} в главном меню и переход на страницу каталога");
        $subcategoryNumber--;

        /**
         * @var $CategoriesList Link[]
         */
        $subcategoriesLinks = $this->getSubcategoriesLinks();
        if (isset($subcategoriesLinks[$subcategoryNumber])) {
            $subcategoriesLinks[$subcategoryNumber]->clickAndGoToPageInCurrentWindow($page);
            return $page;
        } else {
            throw new ATException("Подкатегория {$subcategoryNumber} отсутствует в главном меню");
        }
    }
}