<?php
/**
 * @description Страница оферты
 */

namespace AT\Page\Offer;

use AcceptanceCore\Core\Utils\Waiting;
use AT\BasePage;
use AT\Block\Accordion;
use AT\Page\iPageTemplate;

class Offer extends BasePage implements iPageTemplate
{
    private $accordionBlock;

    public function getAccordionBlock(): Accordion
    {
        if (is_null($this->accordionBlock)) {
            $this->accordionBlock = new Accordion();
        }
        return $this->accordionBlock;
    }

    public function waitForReady()
    {
        parent::waitForReady();
        Waiting::waitForElementVisible(
            $this->getAccordionBlock()->me(),
            10,
            'Не удалось дождаться видимости блока аккордеона'
        );
    }
}