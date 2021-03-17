<?php
namespace Nubesys\Core\Controllers;

use Nubesys\Core\Controllers\Controller;

class CoreWsController extends Controller
{

    protected function getLoaderMethod($p_serviceTypeParam){
        $result = "loadWebService";

        return $result;
    }
    
    protected function loadWebService($p_serviceClass, $p_urlparams){
        
        $this->getDI()->get("responseManager")->setHeader("Content-Type", "application/json");

        if(class_exists($p_serviceClass)){
            
            $wsService                  = new $p_serviceClass($this->getDI());

            $params = array();
            $params['URL']          = $p_urlparams;
            $params['GET']          = $this->getDI()->get('requestManager')->getGET();
            $params['POST']         = $this->getDI()->get('requestManager')->getPOST();
            $params['JSON']         = $this->getDI()->get('requestManager')->getJSON();

            $wsServiceMethodName    = "getMethod";
            //FALTAN LOS METODOS REST Full
            if(isset($p_urlparams[5])){

                $methodName         = \lcfirst(\Phalcon\Text::camelize($p_urlparams[5]));
                
                $wsServiceMethodName      = $methodName . "Method";
            }

            if(method_exists($wsService, $wsServiceMethodName)){

                $wsService->doService($wsServiceMethodName, $params);
                
            }else{

                $this->getDI()->get("responseManager")->setError("Method " . $p_serviceClass . " " . $wsServiceMethodName . " Not Found!");
            }

        }else{
            
            $this->getDI()->get("responseManager")->setError("Service " . $p_serviceClass . " Not Found!");
        }
    }
}