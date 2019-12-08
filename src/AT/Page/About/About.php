<?php
/**
* @description Страница 'Об Универмаге'
*/

namespace AT\Page\About;

use AT\BasePage;
use AT\Block\MobileFooterNavigationSelect;
use AcceptanceCore\Core\Selector;
use AcceptanceCore\Core\WB;
use AcceptanceCore\Core\Utils\Waiting;
use AT\Page\iPageTemplate;

class About extends BasePage implements iPageTemplate
{

    private $mobileFooterNavigationSelect;

    private function getPageTitleSelector(): Selector
    {
        return (new Selector(''))->childByTag('h1');
    }

    private function getMapSelector(): Selector
    {
       return (new Selector(''))->childByAttribute(BasePage::TEST_ATTR_NAME, 'map-info');
    }

    public function getMobileFooterNavigationMenuSelect(): MobileFooterNavigationSelect
    {
        if (is_null($this->mobileFooterNavigationSelect)) {
            $this->mobileFooterNavigationSelect = new MobileFooterNavigationSelect();
        }
        return $this->mobileFooterNavigationSelect;
    }

    public function isBaseContentVisible(): bool
    {
        Waiting::waitForElementVisible($this->getPageTitleSelector(), null, 'Не удалось дождаться видимости заголовка страницы');
        return WB::isElementVisible($this->getMapSelector());
    }

    public function waitForReady()
    {
        parent::waitForReady();
        Waiting::waitForElementVisible(
            $this->getMobileFooterNavigationMenuSelect()->me(),
            10,
            'Не удалось дождаться видимости блока выпадающего меню навигации'
        );
    }
}