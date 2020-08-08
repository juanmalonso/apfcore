<?php
namespace Nubesys\Core\Ui\Components\Portal\V2;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class Message extends VueUiComponent {

    public function mainAction(){
        
        $this->setJsDataVar("icon", $this->getLocal("icon"));
        $this->setJsDataVar("title", $this->getLocal("title"));
        $this->setJsDataVar("message", $this->getLocal("message"));
    }
}