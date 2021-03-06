<?php
namespace Nubesys\Core\Ui\Components\Navigation\SideMenu;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class SideMenu extends VueUiComponent {

    public function mainAction(){
        
        if($this->hasLocal('items')){

            $this->setJsDataVar("items", $this->getLocal('items'));
        }else{

            $this->setJsDataVar("items",[]);
        }

        if($this->hasLocal('user')){

            $this->setJsDataVar("user", $this->getLocal('user'));
        }else {
            
            $this->setJsDataVar("user",false);
        }
    }

    private function getItemAbsoluteUrl($p_path){

        return $this->getDI()->getConfig('main')->url->base . $p_path;
    }
}