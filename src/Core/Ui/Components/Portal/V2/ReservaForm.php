<?php
namespace Nubesys\Core\Ui\Components\Portal\V2;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class ReservaForm extends VueUiComponent {

    public function mainAction(){
        
        $this->setJsDataVar("data", $this->getLocal("data"));
    }
}