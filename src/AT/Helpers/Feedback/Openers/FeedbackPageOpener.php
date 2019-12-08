<?php
/**
* @description Открыватель главной страницы 'Задать вопрос'
*/

namespace AT\Helpers\Feedback\Openers;

use AcceptanceCore\Core\WB;
use AT\Page\Feedback\Feedback;

class FeedbackPageOpener
{
    public function openFeedbackPage(): Feedback
    {
        WB::open('/page/feedback');
        $page = new Feedback();
        $page->waitForReady();
        WB::step('Открылась страница: Задать вопрос');
        return $page;
    }
}