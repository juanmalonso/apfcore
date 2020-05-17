<?php

namespace Nubesys\Core\Ui\Pages;

use Nubesys\Vue\Services\VueUiService;

//COMPONENTS
use Nubesys\Core\Ui\Components\Basic\Button;

class Test extends VueUiService {

    public function mainAction(){

        $this->setTitle("TEST");

        $buttonParams              = array();

        $button                    = new Button($this->getDI());
        $this->placeComponent("main", $button, $buttonParams);
    }
}