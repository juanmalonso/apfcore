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
                    $servicePathPartes      = \array_merge(array($urlparams[1]),  explode("_", $urlparams[2]), array("ws"), explode("_", $urlparams[3]));
                    break;

                case 'uip':
                    $servicePathPartes      = \array_merge(array($urlparams[1]), explode("_", $urlparams[2]),array("ui", "pages"), explode("_", $urlparams[3]));
                    break;

                case 'uid':
                    $servicePathPartes      = \explode("_", $urlparams[1]);
                    break;

                case 'bin':
                    $servicePathPartes      = \array_merge(array($urlparams[1], $urlparams[2], "fs"), explode("_", $urlparams[3]));
                    break;

                case 'flow':
                    $servicePathPartes      = \array_merge(array($urlparams[1], $urlparams[2], "flow"), explode("_", $urlparams[3]));
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