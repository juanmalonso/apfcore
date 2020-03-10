<?php
namespace Nubesys\Core\Ui\Components\App\Selectors\TableList;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class TableList extends VueUiComponent {

    public function mainAction(){
        
        $this->setJsDataVar("fields", $this->getLocal("fields"));
        $this->setJsDataVar("data", $this->getLocal("data"));
    }
}