<?php

namespace Nubesys\Core\Ui\Pages;

use Nubesys\Vue\Services\VueUiService;

//COMPONENTS
use Nubesys\Core\Ui\Components\Navigation\SideMenu\SideMenu;
use Nubesys\Core\Ui\Components\App\Top\TopBar\TopBar;

class Board extends VueUiService {

    public function mainAction(){

        $this->accessControl();

        $this->setTitle($this->getLocal("title"));

        $this->setViewVar("content", "Panel Principal de Prueba");
        
        $this->generateSideMenu();
        $this->generateTopBar();
    }

    //ACCESS CONTROL
    //TODO VER SI HACE FALTA LLEVAR A LA SUPERCLASE PARA LAS APIS
    protected function accessControl(){
        
        if($this->getLocal("accessControl")){

            $loginurl = $this->getDI()->get('config')->main->url->base . "login";
            
            if($this->hasSession("user_loged")){

                if(!$this->getSession("user_loged")){

                    header("Location: " . $loginurl);
                    exit();
                }
            }else{
                
                header("Location: " . $loginurl);
                exit();
            }
        }
    }

    //ROLE SIDE MENU
    protected function generateSideMenu(){
        
        $user                   = $this->getLocal("navigation.user");

        $items                  = $this->getLocal("navigation.items");

        $sideMenuParams             = array();
        $sideMenuParams['user']     = $user;
        $sideMenuParams['items']    = $items;


        $sideMenu                   = new SideMenu($this->getDI());
        $this->placeComponent("side", $sideMenu, $sideMenuParams);
        
    }

    //ROLE SIDE MENU
    protected function generateTopBar(){

        $actions                = array();

        //TODO : ITEMS SEGUN MODULO

        $action                 = new \stdClass();
        $action->label          = "nuevo";
        $action->url            = "";
        $action->icon           = "plus green icon";

        $actions[]              = $action;

        $topBarParams               = array();
        $topBarParams['title']      = "Board";
        $topBarParams['actions']    = $actions;

        $topBar                     = new TopBar($this->getDI());
        $this->placeComponent("top", $topBar, $topBarParams);
        
    }

    //FOOTER
    protected function generateFooter(){

        //TODO : 
    }
}