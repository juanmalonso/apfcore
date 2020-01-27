<?php
namespace Nubesys\Core\Controllers;

use Phalcon\Mvc\Controller as PhalconController;

class Controller extends PhalconController
{
    public function routeAction(){
        
        $result = null;
        
        $urlparams = \Nubesys\Core\Utils\Url::parseUrlParams($this->dispatcher->getParams());

        if(isset($urlparams[0]) && isset($urlparams[1]) && isset($urlparams[2])){

            $serviceType                    = $urlparams[0];
            switch ($serviceType) {

                case 'api':
                    $servicePathPartes      = \array_merge(array('nubesys', $urlparams[1], "ws"), explode("_", $urlparams[2]));
                    break;

                case 'uip':
                    $servicePathPartes      = \array_merge(array('nubesys', $urlparams[1], "ui", "pages"), explode("_", $urlparams[2]));
                    break;

                case 'uid':
                    $servicePathPartes      = \array_merge(array('nubesys', $urlparams[1], "ui", "componets"), explode("_", $urlparams[2]));
                    break;

                case 'bin':
                    $servicePathPartes      = \array_merge(array('nubesys', $urlparams[1], "bin"), explode("_", $urlparams[2]));
                    break;
            }

            $serviceClass                   = implode("\\",array_map(function ($e){ return \Phalcon\Text::camelize($e);}, $servicePathPartes));

            $serviceLoaderMethod            = $this->getLoaderMethod($urlparams[0]);
            
            $result                         = $this->$serviceLoaderMethod($serviceClass, $urlparams);

        } else {
            
            //TODO : ERROR.... NOT FOUND
        }

        return $result;
    }
}