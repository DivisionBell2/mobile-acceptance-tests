<?php
/**
* @description Тест на проверку наличия основных элементов на странице "Задать вопрос"
*/

namespace AT\Test\Mobile\Feedback;

use AT\BaseTest;
use AT\Helpers\Feedback\Openers\FeedbackPageOpener;

class CheckMainElementsOnFeedbackPage extends BaseTest
{
    public function testCheckMainElementsOnFeedbackPage()
    {
        $feedbackPage = (new FeedbackPageOpener())->openFeedbackPage();

        $this->assertTrue(
            $feedbackPage->getMobileFooterNavigationMenuSelect()->isVisible(),
            "Блок выпадающего меню навигации не виден на странице"
        );

        $feedbackBlock = $feedbackPage->getFeedbackBlock();
        $this->assertTrue(
            $feedbackBlock->getFeedbackForm()->isVisible(),
            "Форма обратной связи не видна на странице"
        );

        $this->assertTrue(
            $feedbackBlock->getFeedbackItemsCount() > 0,
            "Блоки вопросов и ответов не видны на странице"
        );

        $this->assertTrue(
            $feedbackBlock->isShowMoreButtonVisible(),
            "Кнопка 'Показать еще' отсутствует на странице"
        );
    }
}