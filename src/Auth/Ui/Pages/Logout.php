<?php

namespace Nubesys\Auth\Ui\Pages;

use Nubesys\Vue\Services\VueUiService;

//COMPONENTS
use Nubesys\Auth\Ui\Components\LoginForm;

class Logout extends VueUiService {

    public function mainAction(){
        
        if($this->hasSession("user_data")){

            $userLogoutEventData                        = array();

            $userLogoutEventData['user']                = array(
                                                            "userid" => $this->getSession("user_data")['id'],
                                                            "userLogin" => $this->getSession("user_data")['login']
                                                        );
                    
            $this->trackEvent("USER-LOGOUT", $userLogoutEventData);
        }
        
        $this->destroySession();

        header("Location: " . $this->getDI()->get('config')->main->url->base . "login");
        exit();
    }
}