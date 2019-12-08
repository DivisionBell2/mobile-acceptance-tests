<?php
/**
 * @description Блок меню навигации в футере
 */

namespace AT\Block\Footer;

use AcceptanceCore\Core\ATException;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\BasePage;
use AT\Block\Link;
use Core\Utils\Arrays;

class FooterNavigationMenu extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'footer-menu';

    private $linksArr = [];

    public function clickOnLinkWithNameAndGoToPage(string $linkName, BasePage $page): BasePage
    {
        /**
         * @var $links Link[]
         */
        $links = $this->linksArr;
        if (count($links) == 0) {
            $links = $this->getLinks();
        }

        $linkNameLowerCase = mb_strtolower($linkName);
        if (isset($links[$linkNameLowerCase])) {
            WB::step("Сделать клик по $linkName (переход на другую страницу)");
            return $links[$linkNameLowerCase]->clickAndGoToPageInCurrentWindow($page);
        } else {
            throw new ATException("Ссылка $linkName отсутствует в навигационном меню футера");
        }

    }

    /**
     * Возвращает массив типа ["универмаг" => object(AT\Block\Link) {...}]
     * @return array
     */
    public function getLinks(bool $useCache = true): array
    {
        if ($useCache && count($this->linksArr) > 0) {
            return $this->linksArr;
        }

        $linksArr = [];
        $visibleFooterNavigationMenu = $this->me()->notHavingClass('sm-hide');
        $footerMenuSelectorsCount = WB::selectorCount($visibleFooterNavigationMenu);
        for ($i = 1; $i <= $footerMenuSelectorsCount; $i++) {
            $linkSelectors = $visibleFooterNavigationMenu->nthOfType($i)->childByTag('a', true);
            $linksCount = WB::selectorCount($linkSelectors);

            for ($j = 1; $j <= $linksCount; $j++) {
                $linkSelector = $linkSelectors->nthOfType($j);
                if (WB::isElementExists($linkSelector)) {
                    $linkName = mb_strtolower(WB::grabTextFrom($linkSelector));
                    $linksArr[$linkName] = new Link((string) $linkSelectors->nthOfType($j));
                }
            }
        }
        Arrays::checkForEmptyArray($linksArr, 'Не найдены ссылки в футере');
        $this->linksArr = $linksArr;
        return $linksArr;
    }
}