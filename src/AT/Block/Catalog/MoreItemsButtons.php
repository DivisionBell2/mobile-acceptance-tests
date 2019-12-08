<?php
/**
 * @description Набор функций для проверки кнопки "загрузить еще" и страничного перехода
 */

namespace AT\Block\Catalog;

use AcceptanceCore\Core\ATException;
use AcceptanceCore\Core\Utils\Waiting;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\Block\Button;
use AT\Block\Link;
use AT\Page\Catalog\Clothes;
use Core\Utils\Arrays;

class MoreItemsButtons extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'more-items';

    private $pagesLinksArr = [];

    private function getShowMoreButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'show-more-button'));
    }

    public function getPagesLinks(bool $useCache = true): array
    {
        if ($useCache && count($this->pagesLinksArr)) {
            return $this->pagesLinksArr;
        }

        $linksArr = [];
        $linkSelector = $this->me()
            ->childByAttribute(self::TEST_ATTR_NAME, 'pages-list')
            ->childByTag('div', true);
        $linksCount = WB::selectorCount($linkSelector);
        for ($i = 1; $i <= $linksCount; $i++) {
            $selector = $linkSelector->nthOfType($i)->childByTag('a');
            if (WB::isElementExists($selector)) {
                $linkPagesNumber = mb_strtolower(WB::grabTextFrom($selector));
                $linksArr[$linkPagesNumber] = new Link((string) $selector);
            }
        }
        Arrays::checkForEmptyArray($linksArr, 'Не найдены пункты постраничного перехода');
        $this->pagesLinksArr = $linksArr;
        return $linksArr;
    }

    public function clickShowMoreButtonAndWaitForLoadCatalogItems(): bool
    {
        $productItemsCountBefore = count((new ProductList())->getProductItems());
        WB::step("Клик по кнопке 'Показать еще' на странице");
        $this->getShowMoreButton()->click();
        $seconds = 3;
        for ($i = 0; $i < $seconds; $i++) {
            $productItemsCountAfter = count((new ProductList())->getProductItems());
            if ($productItemsCountBefore < $productItemsCountAfter) {
                return true;
            }
            //Ожидание появления новых товаров на странице
            Waiting::wait(1);
        }
        throw new ATException('Не удалось дождаться появления товаров за {$seconds} секунды');
    }

    public function clickOnPageLink(int $pageLinkNumber = 2)
    {
        WB::step("Клик по номеру {$pageLinkNumber} страницы каталога");
        /**
         * @var $pageLinks Link[]
         */
        $pageLinks = $this->getPagesLinks();
        if (!isset($pageLinks[$pageLinkNumber])) {
            throw new ATException("Не найдена ссылка на страницу с номером {$pageLinkNumber}");
        }
        $pageLinks[$pageLinkNumber]->clickAndGoToPageInCurrentWindow(new Clothes(), true);
    }

    public function isPageLinkSelected(int $pageLinkNumber = 2): bool
    {
        WB::step("Проверка отображения активной текущей страницы номер {$pageLinkNumber} в пагинаторе");
        $waitingSecondsCounter = 3;
        for ($i = 0; $i < $waitingSecondsCounter; $i++) {
            /**
             * @var $pageLinks Link[]
             */
            $pageLinks = $this->getPagesLinks();
            if (isset($pageLinks[$pageLinkNumber]) && WB::getAttribute($pageLinks[$pageLinkNumber]->me(), "aria-current") == "true") {
                return true;
            }
            //добавлен в связи с тем, что вебдрайвер читает данные со страницы быстрее,
            //чем обновляется DOM после нажатия на кнопку.
            Waiting::wait(1);
        }
        throw new ATException("Текущая страница номер {$pageLinkNumber} не указана активной в течение {$waitingSecondsCounter}  секунд");
    }
}
