<?php
/**
 * @description Открыватель страницы оферты
 */

namespace AT\Helpers\Offer\Openers;

use AcceptanceCore\Core\WB;
use AT\Page\Offer\Offer;

class OfferPageOpener
{
    public function openOfferPage(): Offer
    {
        //TODO Коллега, помни! Когда страница оферты окончательно переедет на nextjs, тебе нужно заменить ссылку на /page/offer
        WB::open('/page/offer2');
        $page = new Offer();
        $page->waitForReady();
        WB::step('Открылась страница: Оферта');
        return $page;
    }
}