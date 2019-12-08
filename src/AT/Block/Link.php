<?php
/**
* @description Объект любой ссылки
*/

namespace AT\Block;

use AcceptanceCore\Core\Utils\Strings;
use AcceptanceCore\Core\WB;
use AT\BaseBlock;
use AT\BasePage;

class Link extends BaseBlock
{
    protected $blockTag = 'a';

    public function click()
    {
        WB::click($this->me());
    }

    public function clickHTML()
    {
        WB::clickHTML($this->me());
    }

    public function getText(): string
    {
        return WB::grabTextFrom($this->me());
    }

    public function getHref(): string
    {
        return WB::getAttribute($this->me(), 'href');
    }

    public function getTextFromTitleAttribute(): string
    {
        return WB::getAttribute($this->me(), 'title');
    }

    public function getLinkClass(): string
    {
        return WB::getAttribute($this->me(), 'class');
    }

    public function isLinkWithName(string $name): bool
    {
        $linkName = $this->getText();
        return Strings::areStringsEqualCi($name, $linkName);
    }

    public function isLinkHasText(string $linkText): bool
    {
        return Strings::isStringFoundIn($linkText, $this->getText());
    }

    public function clickAndGoToPageInCurrentWindow(BasePage $page, bool $htmlClick = false): BasePage
    {
        $htmlClick ? $this->clickHTML() : $this->click();
        $page->waitForReady();
        return $page;
    }

    public function clickAndGoToPageInNewWindow(BasePage $page, bool $htmlClick = false): BasePage
    {
        $htmlClick ? $this->clickHTML() : $this->click();
        WB::switchToNextWindow();
        $page->waitForReady();
        return $page;
    }
}
