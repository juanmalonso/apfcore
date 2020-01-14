<?php

namespace Nubesys\Core\Ui\Pages;

use Nubesys\Vue\Services\VueUiService;

//COMPONENTS
use Nubesys\Core\Ui\Components\Basic\Button;

class Login extends VueUiService {

    public function mainAction($p_params){

        $this->setTitle("Titulo de la Pagina");

        $this->setJsDataVar("test","testvalue");

        $button = new Button($this->getDI());
        $this->placeComponent("content", $button, $p_params);
    }
}