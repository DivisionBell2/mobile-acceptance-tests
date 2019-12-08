<?php
/**
* @description блок c основным сонтентом страницы https://www.butik.ru/page/certificates#top
*/

namespace AT\Block\Certificates;

use AT\BaseBlock;
use AT\Block\Button;
use AT\Block\Certificates\BuyCertificateBlock;
use AcceptanceCore\Core\WB;

class CertificatesBlock extends BaseBlock
{
    protected $blockAttributeName = self::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'certificates-block';

    private $buyCertificateBlock;

    private function getCertificateUseRulesButton(): Button
    {
        return new Button((string) $this->me()->childByAttribute(BaseBlock::TEST_ATTR_NAME, 'certificate-use-rules-button'));
    }

    public function getBuyCertificateBlock(): BuyCertificateBlock
    {
        if (is_null($this->buyCertificateBlock)) {
            $this->buyCertificateBlock = new BuyCertificateBlock();
        }
        return $this->buyCertificateBlock;
    }

    public function isBuyCertificateBlockVisible(): bool
    {
        WB::step("Проверка на видимость блока выбора сертификата для покупки");
        return $this->getBuyCertificateBlock()->isVisible();
    }

    public function isCertificateUseRulesButtonVisible(): bool
    {
        WB::step("Проверка на видимость кнопки 'Правила использования сертификатов'");
        return $this->getCertificateUseRulesButton()->isVisible();
    }
}