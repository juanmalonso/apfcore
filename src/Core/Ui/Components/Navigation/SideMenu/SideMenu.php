<?php
namespace Nubesys\Core\Ui\Components\Navigation\SideMenu;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class SideMenu extends VueUiComponent {

    public function mainAction(){
        
        if($this->hasLocal('items')){

            $this->setJsDataVar("items", $this->sortItems($this->getLocal('items')));
        }else{

            $this->setJsDataVar("items",[]);
        }

        if($this->hasLocal('user')){

            $this->setJsDataVar("user", $this->getLocal('user'));
        }else {
            
            $this->setJsDataVar("user",false);
        }
    }

    function sortItems($p_items){

        $itemsTmp = $p_items;

        usort($itemsTmp, function($a, $b) {
            
            return $a['order'] <=> $b['order'];
        });
        
        return $itemsTmp;
    }
}