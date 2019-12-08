<?php
/**
* @description Открыватель страницы каталога "Одежда"
*/

namespace AT\Helpers\Catalog\Openers;

use AcceptanceCore\Core\WB;
use AT\Page\Catalog\Clothes;
use AT\Page\Feedback\Feedback;

class CatalogClothesPageOpener
{
    public function openCatalogWomenClothesPage(): Clothes
    {
        return (new CatalogClothesPageOpener())->openCatalogClothesPage('zhenshchinam');
    }

    public function openCatalogMenClothesPage(): Clothes
    {
        return (new CatalogClothesPageOpener())->openCatalogClothesPage('muzhchinam');
    }

    private function openCatalogClothesPage(string $genderUrlPart): Clothes
    {
        WB::open("/catalog/$genderUrlPart/odezhda");
        $page = new Clothes();
        $page->waitForReady();
        WB::step('Открылась страница: Одежда');
        return $page;
    }
}