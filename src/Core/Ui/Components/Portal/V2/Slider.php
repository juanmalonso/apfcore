<?php
namespace Nubesys\Core\Ui\Components\Portal\V2;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class Slider extends VueUiComponent {

    public function mainAction(){
        
        $this->setJsDataVar("keyword", $this->getLocal("keyword"));
        $this->setJsDataVar("mes", $this->getLocal("mes"));
        $this->setJsDataVar("paises", $this->getLocal("paises"));
    }
}