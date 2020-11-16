<?php

namespace Nubesys\Auth\Ui\Pages;

use Nubesys\Vue\Services\VueUiService;

//COMPONENTS
use Nubesys\Auth\Ui\Components\LoginForm;

class Logout extends VueUiService {

    public function mainAction(){

        $this->destroySession();

        header("Location: " . $this->getDI()->get('config')->main->url->base . "login");
    }
}