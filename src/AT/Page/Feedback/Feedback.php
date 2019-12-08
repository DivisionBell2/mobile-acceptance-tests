<?php
/**
* @description Страница "Задать вопрос"
*/

namespace AT\Page\Feedback;

use AT\BasePage;
use AT\Block\MobileFooterNavigationSelect;
use AT\Block\Feedback\FeedbackBlock;
use AcceptanceCore\Core\Utils\Waiting;
use AT\Page\iPageTemplate;

class Feedback extends BasePage implements iPageTemplate
{
    private $mobileFooterNavigationSelect;
    private $feedbackBlock;

    public function getMobileFooterNavigationMenuSelect(): MobileFooterNavigationSelect
    {
        if (is_null($this->mobileFooterNavigationSelect)) {
            $this->mobileFooterNavigationSelect = new MobileFooterNavigationSelect();
        }
        return $this->mobileFooterNavigationSelect;
    }

    public function getFeedbackBlock(): FeedbackBlock
    {
        if (is_null($this->feedbackBlock)) {
            $this->feedbackBlock = new FeedbackBlock();
        }
        return $this->feedbackBlock;
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