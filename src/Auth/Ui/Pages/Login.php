<?php

namespace Nubesys\Auth\Ui\Pages;

use Nubesys\Vue\Services\VueUiService;

//COMPONENTS
use Nubesys\Auth\Ui\Components\LoginForm;

class Login extends VueUiService {

    public function mainAction(){

        
        $this->setTitle("LOGIN - Acceso de Usuarios");

        $this->addJsSource("https://cdn.jsdelivr.net/crypto-js/3.1.2/rollups/sha1.js");

        $loginForm               = new LoginForm($this->getDI());
        
        $this->placeComponent("content", $loginForm);
    }
}