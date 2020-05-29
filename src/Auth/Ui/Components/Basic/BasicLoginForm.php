<?php

namespace Nubesys\Auth\Ui\Components\Basic;

use Nubesys\Vue\Ui\Components\VueUiComponent;

use Nubesys\Data\DataSource\DataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Objects as ObjectsDataSource;

class BasicLoginForm extends VueUiComponent {

    public function mainAction(){

        $message    = "";

        if($this->hasPostParam('login') && $this->hasPostParam('password')){
            
            $userData   = false;

            $dataSourceOptions              = array();
            $dataSourceOptions['model']     = "usuario";

            $dataSource                     = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));

            $query                          = array();
            $query['page']                  = 1;
            $query['rows']                  = 1000;
            $query['keyword']               = "objData.login:" . $this->getPostParam('login');

            $result                         = $dataSource->getData($query);
            
            if(\is_array($result) && isset($result['objects'])){
                
                foreach($result['objects'] as $userDataTmp){
                    
                    if($userDataTmp['login'] == $this->getPostParam('login') && $userDataTmp['password'] == $this->getPostParam('password')){

                        $userData = $userDataTmp;
                        break;
                    }
                }
                
                if($userData !== false){
                
                    $this->setSession("user_loged", true);
                    $this->setSession("user_login", $userData['login']);
                    $this->setSession("user_roles", $userData['roles']);
                    $this->setSession("user_firstname", $userData['nombre']);
                    $this->setSession("user_lastname", $userData['apellido']);
                    $this->setSession("user_avatar", "https://www.kindpng.com/picc/b/269/2697881.png");
    
                    //TODO : Falta majejo de privilegios
    
                    //$rolestr      = $userData['role'];
                    $startpage      = $this->getDI()->get('config')->main->url->base . 'board/';
    
                    $this->setSession("user_startpage", $startpage);
    
                    header("Location: " . $startpage);
                }
            }
        }

        $this->setJsDataVar("message",$message);
    }
}