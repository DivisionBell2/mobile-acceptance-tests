<?php
/**
* @description блок c основным контентом страницы 'Задать вопрос'
*/

namespace AT\Block\Feedback;

use AT\BaseBlock;
use AT\Block\Button;
use Core\Utils\Arrays;
use AcceptanceCore\Core\WB;
use AcceptanceCore\Core\Selector;
use AcceptanceCore\Core\ATException;
use AcceptanceCore\Core\Utils\Waiting;

class FeedbackBlock extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'feedback';

    private $feedbackForm;

    public function getFeedbackForm(): FeedbackForm
    {
        if (is_null($this->feedbackForm)) {
            $this->feedbackForm = new FeedbackForm();
        }
        return $this->feedbackForm;
    }

    private function getShowMoreButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'show-more-button'));
    }

    private function getFeedbackItems(): array
    {
        $feedbackItemsArr = [];
        $feedbackItemSelector = $this->me()->childByTag('div', true);
        $feedbackItemsCount = WB::selectorCount($feedbackItemSelector);
        for ($i = 1; $i <= $feedbackItemsCount; $i++) {
            if (WB::isElementExists($feedbackItemSelector->nthOfType($i)
                ->childByAttribute(self::TEST_ATTR_NAME, 'feedback-item'))) {
                $feedbackItemsArr[$i] = new FeedbackItem((string) $feedbackItemSelector->nthOfType($i));
            }
        }
        Arrays::checkForEmptyArray($feedbackItemsArr, 'Не найдены кнопки в аккордеоне');
        return $feedbackItemsArr;
    }

    private function getAboutSentMessageInfoSelector(): Selector
    {
        return (new Selector(''))->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'about-sent-message-info');
    }

    public function getFeedbackItemsCount(): int
    {
        return count($this->getFeedbackItems());
    }

    public function isShowMoreButtonVisible(): bool
    {
        WB::step("Проверка на видимость кнопки 'Показать еще'");
        return WB::isElementVisible($this->getShowMoreButton());
    }

    public function clickShowMoreButtonAndWaitForLoadNewFeedbackItems(): bool
    {
        WB::step("Проверка появления новых feedback-item на странице после клика по кнопке 'Показать еще'");
        $fedbackItemsAmountBeforeClick = $this->getFeedbackItemsCount();
        $fedbackItemsAmountAfterClick = 0;
        $waitingSecondsCounter = 5;
        $this->getShowMoreButton()->click();
        for ($i = 0; $i < $waitingSecondsCounter; $i++) {
            $fedbackItemsAmountAfterClick = $this->getFeedbackItemsCount();
            if ($fedbackItemsAmountBeforeClick < $fedbackItemsAmountAfterClick) {
                return true;
            }
            //добавлен в связи с тем, что вебдрайвер читает данные со страницы быстрее,
            //чем обновляется DOM после нажатия на кнопку.
            Waiting::wait(1);
        }
        throw new ATException("После нажатия кнопки 'Показать еще', новые feedback-item не появились на странице в течение " . $waitingSecondsCounter . " секунд");
    }

    public function isAboutSentMessageInfoVisible(): bool
    {
        WB::step("Проверка на видимость записи о том, что сообщение отправлено намодерацию");
        return WB::isElementVisible($this->getAboutSentMessageInfoSelector());
    }
}