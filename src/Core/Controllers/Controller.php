<?php
namespace Nubesys\Core\Controllers;

use Phalcon\Mvc\Controller as PhalconController;

class Controller extends PhalconController
{
    public function routeAction(){
        
        $result = null;
        
        $urlparams = \Nubesys\Core\Utils\Url::parseUrlParams($this->dispatcher->getParams());
        var_dump($urlparams);
        if(isset($urlparams[0]) && isset($urlparams[1]) && isset($urlparams[2])){

            $serviceType                    = "ui";
            switch ($urlparams[0]) {

                case 'api':
                    $serviceType            = "ws";
                    break;

                case 'uip':
                    $serviceType            = "ui";
                    break;

                case 'uid':
                    $serviceType            = "ui";
                    break;

                case 'bin':
                    $serviceType            = "bin";
                    break;
            }

            $servicePathPartes              = \array_merge(array('nubesys', $urlparams[1], $serviceType), explode("_", $urlparams[2]));
            var_dump($servicePathPartes);
            if($urlparams[0] == "uip"){

                $serviceClass                   = $urlparams[2];
            }else{

                // TODO : Servicios AJAX y APIS
                $servicePathPartes              = \array_merge(array('nubesys', $urlparams[1], $urlparams[0]), explode("_", $urlparams[2]));

                $serviceClass                   = implode("\\",array_map(function ($e){ return \Phalcon\Text::camelize($e);}, $servicePathPartes));
            }

            var_dump($serviceClass);
            exit();
            $serviceLoaderMethod            = $this->getLoaderMethod($urlparams[0]);
            
            $result                         = $this->$serviceLoaderMethod($serviceClass, $urlparams);

        } else {
            
            //TODO : ERROR.... NOT FOUND
        }

        return $result;
    }
}