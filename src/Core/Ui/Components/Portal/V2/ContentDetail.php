<?php
namespace Nubesys\Core\Ui\Components\Portal\V2;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class ContentDetail extends VueUiComponent {

    public function mainAction(){
        
        $this->setJsDataVar("data", $this->getLocal("data"));
        $this->setJsDataVar("sliderImgSrcMap", $this->getLocal("sliderImgSrcMap"));
    }
}