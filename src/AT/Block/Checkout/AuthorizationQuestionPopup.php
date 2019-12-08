<?php
/**
 * @description Блок попапа в корзине с предложением авторизации
 */

namespace AT\Block\Checkout;

use AcceptanceCore\Core\Utils\Waiting;
use AT\BaseBlock;
use AT\Block\Button;

class AuthorizationQuestionPopup extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'admission-discount';

    private function getNotNowButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(self::TEST_ATTR_NAME, 'not-now'));
    }

    public function clickOnNotNowButton()
    {
        $this->getNotNowButton()->click();
        Waiting::waitForElementNotVisible(
            $this->me(),
            3,
            "Попап с предложением авторизации остался на месте"
        );
    }
}