<?php
/**
* @description Открыватель главной страницы 'Возврат'
*/

namespace AT\Helpers\Refund\Openers;

use AcceptanceCore\Core\WB;
use AT\Page\Refund\Refund;

class RefundPageOpener
{
    public function openRefundPage(): Refund
    {
        WB::open('/page/refund');
        $page = new Refund();
        $page->waitForReady();
        WB::step('Открылась страница: Возврат');
        return $page;
    }
}