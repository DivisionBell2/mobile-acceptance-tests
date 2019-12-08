<?php
/**
* @description Тест на проверку наличия основных элементов на странице сертификатов
*/

namespace AT\Test\Mobile\Certificates;

use AT\BaseTest;
use AT\Helpers\Certificates\Openers\CertificatesPageOpener;

class CheckMainElementsOnCertificatesPage extends BaseTest
{
    public function testCheckMainElementsOnCertificatesPage()
    {
        $certificatesPage = (new CertificatesPageOpener())->openCertificatesPage();

        $this->assertTrue(
            $certificatesPage->getMobileFooterNavigationMenuSelect()->isVisible(),
            "Блок выпадающего меню навигации не виден на странице"
        );

        $certificatesBlock = $certificatesPage->getCertificatesBlock();
        $this->assertTrue(
            $certificatesBlock->isBuyCertificateBlockVisible(),
            'Блок выбора сертификата для покупки присутствует на странице'
        );

        $this->assertTrue(
            $certificatesBlock->isCertificateUseRulesButtonVisible(),
            'Кнопка "Правила использования сертификатов" присутствует на странице'
        );
    }
}