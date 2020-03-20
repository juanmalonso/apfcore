<?php
namespace Nubesys\Core\Ui\Components\App\Top\TopBar;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class TopBar extends VueUiComponent {

    public function mainAction(){

        $this->registerReference("topbar");

        $this->setJsDataVar("title", $this->getLocal("title"));
        $this->setJsDataVar("actions", $this->getLocal("actions"));
    }
}