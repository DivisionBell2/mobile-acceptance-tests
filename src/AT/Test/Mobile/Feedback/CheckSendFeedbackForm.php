<?php
/**
* @description Тест на проверку заполнения формы обратной связи и отправки сообщения на странице "Задать вопрос".
*/

namespace AT\Test\Mobile\Feedback;

use AT\BaseTest;
use AT\Helpers\Feedback\Openers\FeedbackPageOpener;

class CheckSendFeedbackForm extends BaseTest
{
    public function testCheckSendFeedbackForm()
    {
        $feedbackPage = (new FeedbackPageOpener())->openFeedbackPage();
        $feedbackBlock = $feedbackPage->getFeedbackBlock();
        $feedbackForm = $feedbackPage->getFeedbackBlock()->getFeedbackForm();
        $feedbackForm->fillFeedbackFormAndSendMessage();

        $this->assertTrue(
            $feedbackBlock->isAboutSentMessageInfoVisible(),
            'Информация об отправке сообщения на модерацию отсутствует на странице'
        );
    }
}
