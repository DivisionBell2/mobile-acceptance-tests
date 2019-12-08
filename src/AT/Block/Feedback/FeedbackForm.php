<?php
/**
* @description Блок формы обратной связи
*/

namespace AT\Block\Feedback;

use AT\BaseBlock;
use AcceptanceCore\Core\Selector;
use AT\Block\Input;
use AT\Block\Button;
use AcceptanceCore\Core\WB;
use AcceptanceCore\Core\Utils\Waiting;
use AT\Helpers\RegisterUser\UserDataGenerator;

class FeedbackForm extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'feedback-form';

    private $inputUserName;
    private $inputUserEmail;

    private function getUserNameInput(): Input
    {
        if (is_null($this->inputUserName)) {
            $this->inputUserName = new Input((string) $this->me()->childByAttribute('name', 'name'));
        }
        return $this->inputUserName;
    }

    private function getUserEmailInput(): Input
    {
        if (is_null($this->inputUserEmail)) {
            $this->inputUserEmail = new Input((string) $this->me()->childByAttribute('name', 'email'));
        }
        return $this->inputUserEmail;
    }

    private function getQuestionTextareaSelector(): Selector
    {
        return new Selector((string) $this->me()->childByAttribute('name', 'question_text'));
    }

    private function getSendButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'send-button'));
    }

    public function fillFeedbackFormAndSendMessage(string $userName = 'Ivan Ivanov', ?string $userEmail = null, string $questionText = 'Тестовый вопрос')
    {
        if (is_null($userEmail)) {
            $userDataGenerator = new UserDataGenerator();
            $userEmail = $userDataGenerator->getRandomEmail();
        }
        WB::step("Ввод $userName в текстовое поле 'Имя'");
        $this->getUserNameInput()->input($userName);
        WB::step("Ввод $userEmail в текстовое поле 'E-mail'");
        $this->getUserEmailInput()->input($userEmail);
        WB::step("Ввод '$questionText' в текстовое поле 'Вопрос'");
        WB::fillField($this->getQuestionTextareaSelector(), $questionText);
        WB::step("Клик по кнопке 'Отправить' в форме обратной связи");
        $this->getSendButton()->click();
        Waiting::waitForElementNotVisible($this->me(), 15, 'Форма обратной связи осталась на странице');
    }
}
