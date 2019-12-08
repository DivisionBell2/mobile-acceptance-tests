<?php
/**
* @description Открыватель главной страницы 'Подарочные сертификаты'
*/

namespace AT\Helpers\Certificates\Openers;

use AcceptanceCore\Core\WB;
use AT\Page\Certificates\Certificates;

class CertificatesPageOpener
{
    public function openCertificatesPage(): Certificates
    {
        WB::open('/page/certificates');
        $page = new Certificates();
        $page->waitForReady();
        WB::step('Открылась страница: Подарочные сертификаты');
        return $page;
    }
}