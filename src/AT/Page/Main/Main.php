<?php
/**
* @description Главная страница сайта butik.ru
*/

namespace AT\Page\Main;

use AT\BasePage;
use AT\Block\Button;
use AT\Block\Footer\FooterNavigationMenu;
use AT\Block\HeaderMenu\MainMenu;
use AcceptanceCore\Core\WB;
use AcceptanceCore\Core\Selector;
use AcceptanceCore\Core\Utils\Waiting;
use AT\Page\iPageTemplate;

class Main extends BasePage implements iPageTemplate
{
    private $footerNavigationMenuBlock;
    private $mainMenu;

    private function getOpenMainMenuButton(): Button
    {
        return new Button((string) (new Selector(''))->childByAttribute(BasePage::TEST_ATTR_NAME, 'mobile-header-menu'));
    }

    public function getFooterNavigationMenuBlock(): FooterNavigationMenu
    {
        if (is_null($this->footerNavigationMenuBlock)) {
            $this->footerNavigationMenuBlock = new FooterNavigationMenu();
        }
        return $this->footerNavigationMenuBlock;
    }

    public function getMainMenu(): MainMenu
    {
        if (is_null($this->mainMenu)) {
            $this->mainMenu = new MainMenu();
        }
        return $this->mainMenu;
    }

    public function openAndGetMainMenuBlock(): MainMenu
    {
        WB::step("Клик по кнопке главного меню");
        $this->getOpenMainMenuButton()->clickHTML();
        //wait добавлен по причине плавного появления главного меню.
        //Вебдрайвер срабатывает быстрее, чем отображается меню,
        //пытаясь нажать на кнопку в тех координатах, где ее уже нет.
        Waiting::wait(1);
        return $this->getMainMenu();
    }

    public function waitForReady()
    {
        parent::waitForReady();
        Waiting::waitForElementVisible(
            $this->getOpenMainMenuButton()->me(),
            35,
            'Не удалось дождаться видимости кнопки открытия главного меню'
        );
    }
}
