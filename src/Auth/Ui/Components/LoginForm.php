<?php

namespace Nubesys\Auth\Ui\Components;

use Nubesys\Vue\Ui\Components\VueUiComponent;
use Nubesys\Auth\User;

class LoginForm extends VueUiComponent {

    public function mainAction(){

        $message    = "";

        if($this->hasPostParam('login') && $this->hasPostParam('password')){

            $userManager                    = new User($this->getDI());
            
            $userData                       = $userManager->loginUser($this->getPostParam('login'), $this->getPostParam('password'));
            
            if($userData){

                if(isset($userData['password_reset'])){

                    if($userData['password_reset']){

                        header("Location: " . $this->getDI()->get('config')->main->url->base . 'password');
                        exit();
                    }
                }

                $this->setSession("user_loged", true);
                $this->setSession("user_data", $userData);

                $startpage                  = $this->getDI()->get('config')->main->url->base . 'board/';

                if($userData['role']['path']){

                    $startpage              = $this->getDI()->get('config')->main->url->base . $userData['role']['path'];
                }
    
                $this->setSession("user_startpage", $startpage);

                header("Location: " . $startpage);
                exit();

            }else{

                $message                    = "Usuario y Contraseña Invalidos!";
            }
        }

        $this->setJsDataVar("message",$message);
    }
}