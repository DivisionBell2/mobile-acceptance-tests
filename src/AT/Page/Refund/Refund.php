<?php
/**
* @description Страница возврата
*/

namespace AT\Page\Refund;

use AT\BasePage;
use AT\Block\MobileFooterNavigationSelect;
use AT\Block\Refund\AboutRefundBlock;
use AcceptanceCore\Core\Utils\Waiting;
use AT\Page\iPageTemplate;

class Refund extends BasePage implements iPageTemplate
{
    private $mobileFooterNavigationSelect;
    private $aboutRefundBlock;

    public function getMobileFooterNavigationMenuSelect(): MobileFooterNavigationSelect
    {
        if (is_null($this->mobileFooterNavigationSelect)) {
            $this->mobileFooterNavigationSelect = new MobileFooterNavigationSelect();
        }
        return $this->mobileFooterNavigationSelect;
    }

    public function getAboutRefundBlock(): AboutRefundBlock
    {
        if (is_null($this->aboutRefundBlock)) {
            $this->aboutRefundBlock = new AboutRefundBlock();
        }
        return $this->aboutRefundBlock;
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