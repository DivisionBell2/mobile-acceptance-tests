<?php
/**
* @description Страница сертификатов
*/

namespace AT\Page\Certificates;

use AT\BasePage;
use AT\Block\MobileFooterNavigationSelect;
use AT\Block\Certificates\CertificatesBlock;
use AcceptanceCore\Core\Utils\Waiting;
use AT\Page\iPageTemplate;

class Certificates extends BasePage implements iPageTemplate
{
    private $mobileFooterNavigationSelect;
    private $certificatesBlock;

    public function getMobileFooterNavigationMenuSelect(): MobileFooterNavigationSelect
    {
        if (is_null($this->mobileFooterNavigationSelect)) {
            $this->mobileFooterNavigationSelect = new MobileFooterNavigationSelect();
        }
        return $this->mobileFooterNavigationSelect;
    }

    public function getCertificatesBlock(): CertificatesBlock
    {
        if (is_null($this->certificatesBlock)) {
            $this->certificatesBlock = new CertificatesBlock();
        }
        return $this->certificatesBlock;
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