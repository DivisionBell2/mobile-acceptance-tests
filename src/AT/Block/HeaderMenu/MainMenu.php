<?php
/**
* @description Блок главного меню
*/

namespace AT\Block\HeaderMenu;

use AT\BaseBlock;
use AT\Block\HeaderMenu\UserMenuBlock;

class MainMenu extends BaseBlock
{
    protected $blockAttributeName = BaseBlock::TEST_ATTR_NAME;
    protected $blockAttributeValue = 'mobile-main-menu';

    private $userMenuBlock;
    private $categoriesListBlock;

    public function getUserMenu(): UserMenuBlock
    {
        if (is_null($this->userMenuBlock)) {
            $this->userMenuBlock = new UserMenuBlock();
        }
        return $this->userMenuBlock;
    }

    public function getCategoriesList(): CategoriesList
    {
        if (is_null($this->categoriesListBlock)) {
            $this->categoriesListBlock = new CategoriesList();
        }
        return $this->categoriesListBlock;
    }
}
