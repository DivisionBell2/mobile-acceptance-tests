<?php
/**
 * @description блок выбора номинала сертификата для покупки
 */

namespace AT\Block\Certificates;

use AT\BaseBlock;

class BuyCertificateBlock extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'buy-certificate-block';
}