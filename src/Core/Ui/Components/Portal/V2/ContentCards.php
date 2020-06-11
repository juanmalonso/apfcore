<?php
namespace Nubesys\Core\Ui\Components\Portal\V2;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class ContentCards extends VueUiComponent {

    public function mainAction(){
        
        $this->setJsDataVar("data", $this->getLocal("data"));
        $this->setJsDataVar("urlLinkMap", $this->getLocal("urlLinkMap"));
        $this->setJsDataVar("imgSrcMap", $this->getLocal("imgSrcMap"));
    }
}