<?php
/**
* @description Тест на проверку наличия работы кнопки "Показать еще"
*/

namespace AT\Test\Mobile\Feedback;

use AT\BaseTest;
use AT\Helpers\Feedback\Openers\FeedbackPageOpener;

class CheckShowMoreButtonWorkingOnFeedbackPage extends BaseTest
{
    public function testCheckShowMoreButtonWorkingOnFeedbackPage()
    {
        $feedbackPage = (new FeedbackPageOpener())->openFeedbackPage();
        $feedbackBlock = $feedbackPage->getFeedbackBlock();

        $this->assertTrue(
            $feedbackBlock->clickShowMoreButtonAndWaitForLoadNewFeedbackItems(),
            'После нажатия кнопки не показывается больше опубликованных вопросов и отзывов'
        );
    }
}