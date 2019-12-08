<?php
/**
 * @description Блок хедера каталога
 */

namespace AT\Block\Catalog;

use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\Button;

class CatalogHeader extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'mobile-catalog-header';

    private $categoriesList;
    private $sortBlock;

    private function getGenderCategoriesButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'mobile-gender-categories'));
    }

    public function getSortBlock(): SortButtons
    {
        if (is_null($this->sortBlock)) {
            $this->sortBlock = new SortButtons();
        }
        return $this->sortBlock;
    }

    public function getCategoriesList(): CategoriesList
    {
        if (is_null($this->categoriesList)) {
            $this->categoriesList = new CategoriesList();
        }
        return $this->categoriesList;
    }

    public function clickOnGenderCategoriesButton(): CategoriesList
    {
        WB::step("Клик по кнопке 'Категории' и переход к списку категорий");
        $this->getGenderCategoriesButton()->click();
        $categoriesList = $this->getCategoriesList();
        Waiting::waitForElementVisible($categoriesList->me(), 5, "Не удалось дождаться появления списка категорий");
        return $categoriesList;
    }

    public function getNameCategory(): string
    {
        WB::step("Получение имени в заголовке выбранной категории");
        return mb_strtolower(WB::grabTextFrom($this->getGenderCategoriesButton()->me()));
    }
}