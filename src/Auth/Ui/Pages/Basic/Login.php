<?php

namespace Nubesys\Auth\Ui\Pages\Basic;

use Nubesys\Vue\Services\VueUiService;

//COMPONENTS
use Nubesys\Auth\Ui\Components\Basic\BasicLoginForm;

class Login extends VueUiService {

    public function mainAction($p_params){

        $this->setTitle("Titulo de Ejemplo la Pagina");

        $this->setJsDataVar("test","testvalue");

        $form = new BasicLoginForm($this->getDI());
        $this->placeComponent("content", $form, $p_params);
        
    }
}