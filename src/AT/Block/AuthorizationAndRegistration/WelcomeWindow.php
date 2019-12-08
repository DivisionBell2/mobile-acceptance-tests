<?php
/**
 * @description Окно приветствия после регистрации нового пользователя
 */

namespace AT\Block\AuthorizationAndRegistration;

use AT\BaseBlock;

class WelcomeWindow extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'welcome-window';
}