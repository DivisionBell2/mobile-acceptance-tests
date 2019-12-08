<?php
/**
 * @description Блок информации со страницы успешного заказа
 */

namespace AT\Block\Checkout;

use AcceptanceCore\Core\WB;
use AT\BaseBlock;

class SuccessfulOrderInfo extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'after-order';

    public function getFullName(): string
    {
        return WB::grabTextFrom($this->me()
            ->childByAttribute(self::TEST_ATTR_NAME, 'name-after-order'));
    }

    public function getPhoneNumber(): string
    {
        return WB::grabTextFrom($this->me()
            ->childByAttribute(self::TEST_ATTR_NAME, 'number-after-order'));
    }
}