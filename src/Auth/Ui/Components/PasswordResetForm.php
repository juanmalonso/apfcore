<?php

namespace Nubesys\Auth\Ui\Components;

use Nubesys\Vue\Ui\Components\VueUiComponent;
use Nubesys\Auth\User;

class PasswordResetForm extends VueUiComponent {

    public function mainAction(){

        $message    = "";

        if($this->hasPostParam('password')){
            
            $userManager                    = new User($this->getDI());
            
            if($this->hasSession("user_loged") && $this->hasSession("user_data")){

                if($this->getSession("user_loged") == true){
                    
                    $resetPasswordResult = $userManager->resetPassword($this->getSession("user_data")['model'], $this->getSession("user_data")['id'], $this->hasPostParam('password'));

                    if($resetPasswordResult){

                        header("Location: " . $this->getDI()->get('config')->main->url->base . "logout");
                    }
                }
            }
        }

        $this->setJsDataVar("message",$message);
    }
}