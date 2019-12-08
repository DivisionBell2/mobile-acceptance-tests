<?php
/**
* @description Тест на проверку наличия основных элементов на странице возврата
*/

namespace AT\Test\Mobile\Refund;

use AT\BaseTest;
use AT\Helpers\Refund\Openers\RefundPageOpener;

class CheckMainElementsOnRefundPage extends BaseTest
{
    public function testCheckMainElementsOnRefundPage()
    {
        $refundPage = (new RefundPageOpener)->openRefundPage();

        $this->assertTrue(
            $refundPage->getMobileFooterNavigationMenuSelect()->isVisible(),
            "Блок выпадающего меню навигации не виден на странице"
        );

        $aboutRefundBlock = $refundPage->getAboutRefundBlock();
        $this->assertTrue(
            $aboutRefundBlock->isRegionsInfoRefundTextNotVisible(),
            'Блок информации о возврате в регионах виден на странице'
        );

        $this->assertTrue(
            $aboutRefundBlock->isMoscowInfoRefundTextNotVisible(),
            'Блок информации о возврате в Москве и Московской области виден на странице'
        );

        $aboutRefundBlock->clickOnRegionsAccordionButton();
        $this->assertTrue(
            $aboutRefundBlock->isRegionsInfoRefundTextVisible(),
            'Блок информации о возврате в регионах не виден на странице'
        );

        $aboutRefundBlock->clickOnMoscowAccordionButton();
        $this->assertTrue(
            $aboutRefundBlock->isMoscowInfoRefundTextVisible(),
            'Блок информации о возврате в Москве и Московской области не виден на странице'
        );
    }
}