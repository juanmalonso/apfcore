<?php
namespace Nubesys\Core\Ui\Components\Portal\V2;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class ImageCards extends VueUiComponent {

    public function mainAction(){
        
        $this->setJsDataVar("title", $this->getLocal("title"));
        $this->setJsDataVar("data", $this->getLocal("data"));
        $this->setJsDataVar("urlLinkMap", $this->getLocal("urlLinkMap"));
        $this->setJsDataVar("bigImgSrcMap", $this->getLocal("bigImgSrcMap"));
        $this->setJsDataVar("smallImgSrcMap", $this->getLocal("smallImgSrcMap"));
        $this->setJsDataVar("size", $this->getLocal("size"));
    }
}