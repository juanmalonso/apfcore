<?php

namespace Nubesys\Auth\Ui\Components\Basic;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class BasicLoginForm extends VueUiComponent {

    public function mainAction(){

        $message    = "";

        if(isset($p_params['POST']['login']) && isset($p_params['POST']['password'])){

            $user       = $p_params['POST']['login'];
            $pass       = $p_params['POST']['password'];

            //V1 - ACCESO POR CONFIGURACION
            $userData   = false;
            foreach($this->getDI()->get('config')->users->users->toArray() as $userDataTmp){

                if($userDataTmp['login'] == $user && $userDataTmp['password'] == $pass){

                    $userData = $userDataTmp;
                    break;
                }
            }

            if($userData !== false){

                $this->sessionSet("user_loged", true);
                $this->sessionSet("user_login", $userData['login']);
                $this->sessionSet("user_role", $userData['role']);
                $this->sessionSet("user_firstname", $userData['first_name']);
                $this->sessionSet("user_lastname", $userData['last_name']);
                $this->sessionSet("user_avatar", $userData['avatar']);

                //TODO : Falta majejo de privilegios

                $rolestr        = $userData['role'];
                $startpage      = $this->getDI()->get('config')->main->url->base . $this->getDI()->get('config')->users->roles->$rolestr->startpage;

                $this->sessionSet("user_startpage", $startpage);

                header("Location: " . $startpage);
            }

        }

        $this->setJsDataVar("message",$message);
    }
}