<?php

namespace Nubesys\Auth\Ui\Components\Basic;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class BasicLoginForm extends VueUiComponent {

    public function mainAction(){

        $message    = "";

        if($this->hasPostParam('login') && $this->hasPostParam('password')){
            
            $userData   = false;
            foreach($this->getDI()->get('config')->users->users->toArray() as $userDataTmp){

                if($userDataTmp['login'] == $this->getPostParam('login') && $userDataTmp['password'] == $this->getPostParam('password')){

                    $userData = $userDataTmp;
                    break;
                }
            }

            if($userData !== false){
                
                $this->setSession("user_loged", true);
                $this->setSession("user_login", $userData['login']);
                $this->setSession("user_role", $userData['role']);
                $this->setSession("user_firstname", $userData['first_name']);
                $this->setSession("user_lastname", $userData['last_name']);
                $this->setSession("user_avatar", $userData['avatar']['url']);

                //TODO : Falta majejo de privilegios

                $rolestr        = $userData['role'];
                $startpage      = $this->getDI()->get('config')->main->url->base . $this->getDI()->get('config')->users->roles->$rolestr->startpage;

                $this->setSession("user_startpage", $startpage);

                header("Location: " . $startpage);
            }
        }

        $this->setJsDataVar("message",$message);
    }
}