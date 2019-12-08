<?php
/**
 * @description Тест на проверку наличия основных элементов на оферты
 */

namespace AT\Test\Mobile\Offer;

use AT\BaseTest;
use AT\Helpers\Offer\Openers\OfferPageOpener;

class CheckMainElementsOnOfferPage  extends BaseTest
{
    public function testCheckMainElementsOnOfferPage()
    {
        $offerPage = (new OfferPageOpener())->openOfferPage();
        $accordion = $offerPage->getAccordionBlock();
        $accordionButtons = $accordion->getAccordionButtons();

        for ($i = 1; $i <= count($accordionButtons); $i++) {
            $accordion->clickOnAccordionButton($i);

            $this->assertTrue(
                $accordion->isContentSelectorVisible($i),
                'Блок контента не появился после клика по кнопке аккордеона'
            );

            $accordion->clickOnAccordionButton($i);

            $this->assertTrue(
                $accordion->isContentSelectorNotVisible($i),
                'Блок контента не закрылся после второго клика по кнопке аккордеона'
            );
        }
    }
}