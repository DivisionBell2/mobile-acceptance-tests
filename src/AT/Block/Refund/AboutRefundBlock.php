<?php
/**
* @description блок c основным сонтентом страницы https://www.butik.ru/page/refund#top
*/

namespace AT\Block\Refund;

use AT\BaseBlock;
use AT\Block\Accordion;
use AcceptanceCore\Core\WB;
use AT\Page\Refund\Refund;
use AcceptanceCore\Core\Selector;
use AcceptanceCore\Core\Utils\Waiting;

class AboutRefundBlock extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'about-refund';

    private $accordionBlock;

    public function getAccordionBlock(): Accordion
    {
        if (is_null($this->accordionBlock)) {
            $this->accordionBlock = new Accordion();
        }
        return $this->accordionBlock;
    }

    private function getMoscowInfoRefundTextSelector(): Selector
    {
        return $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'moscow-info');
    }

    private function getRegionsInfoRefundTextSelector(): Selector
    {
        return $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'regions-info');
    }

    public function isMoscowInfoRefundTextVisible(): bool
    {
        WB::step("Проверка на видимость блока информации о возврате в Москве и Московской области");
        return WB::isElementVisible($this->getMoscowInfoRefundTextSelector());
    }

    public function isMoscowInfoRefundTextNotVisible(): bool
    {
        WB::step("Проверка на видимость блока информации о возврате в Москве и Московской области");
        return !$this->isMoscowInfoRefundTextVisible();
    }

    public function isRegionsInfoRefundTextNotVisible(): bool
    {
        WB::step("Проверка на видимость блока информации о возврате в регионах");
        return !$this->isRegionsInfoRefundTextVisible();
    }

    public function isRegionsInfoRefundTextVisible(): bool
    {
        WB::step("Проверка на видимость блока информации о возврате в регионах");
        return WB::isElementVisible($this->getRegionsInfoRefundTextSelector());
    }

    public function clickOnMoscowAccordionButton(): Refund
    {
        WB::step("Нажата кнопка 'Москва и Московская область'");
        $this->getAccordionBlock()->clickOnAccordionButtonByIndex(1);
        $refundPage = new Refund();
        $refundPage->waitForReady();
        return $refundPage;
    }

    public function clickOnRegionsAccordionButton(): Refund
    {
        WB::step("Проверка количества опубликованных вопросв после нажатия на кнопку 'Показать еще'");
        $this->getAccordionBlock()->clickOnAccordionButtonByIndex(2);
        $refundPage = new Refund();
        $refundPage->waitForReady();
        return $refundPage;
    }
}