<?php

namespace Nubesys\Core\Ui\Pages;

use Nubesys\Core\Services\UiService;

//use Nubesys\Vue\Services\VueUiService;

//COMPONENTS
//use Nubesys\Core\Ui\Components\Basic\Button;

class Login extends UiService {

    public function mainAction($p_params){

        $this->setTitle("Titulo de Ejemplo la Pagina");

        /*
        $this->setJsDataVar("test","testvalue");

        $button = new Button($this->getDI());
        $this->placeComponent("content", $button, $p_params);
        */
    }
}