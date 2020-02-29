<?php

namespace Nubesys\Core\Ui\Pages;

use Nubesys\Vue\Services\VueUiService;

//COMPONENTS
use Nubesys\Core\Ui\Components\Navigation\SideMenu\SideMenu;

class Board extends VueUiService {

    public function mainAction(){

        $this->accessControl(true);

        $this->setTitle("BOARD - Tablero Principal");

        $this->setViewVar("content", "Panel Principal de Prueba");
        
        $this->generateSideMenu();
    }

    //ROLE SIDE MENU
    protected function generateSideMenu(){
        
        $items                  = array();

        //TODO ITEMS DE CONFIG SEGUN ROL

        $userItem               = new \stdClass();
        $userItem->label        = "SALIR";
        $userItem->url          = $this->getDI()->get('config')->main->url->base . "logout/";
        $userItem->icon         = "sign out alternate red icon";

        $items[]                = $userItem;

        $sideMenuParams               = array();
        $sideMenuParams['items']      = $items;

        $sideMenu               = new SideMenu($this->getDI());
        $this->placeComponent("sidebar", $sideMenu, $sideMenuParams);
        
    }

    //FOOTER
    protected function generateFooter(){

        //TODO : 
    }
}