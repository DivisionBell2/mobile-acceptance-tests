<?php
/**
 * @description Базовый класс для всех блоков в проекте
 */

namespace AT;

use AcceptanceCore\Core\Block;

abstract class BaseBlock extends Block
{
    const TEST_ATTR_NAME = 'data-test';
}