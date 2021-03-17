<?php
namespace Nubesys\Core\Controllers;

use Nubesys\Core\Controllers\Controller;

class CoreUiController extends Controller
{

    protected function getLoaderMethod($p_serviceTypeParam){
        $result = "loadUiPage";
        
        if($p_serviceTypeParam == "uid"){

            $result = "loadUiService";
        }

        return $result;
    }
    
    protected function loadUiPage($p_serviceClass, $p_urlparams){
        
        if(class_exists($p_serviceClass)){
            
            $uiService              = new $p_serviceClass($this->getDI());
            
            $params = array();
            $params['URL']          = $p_urlparams;
            $params['GET']          = $this->getDI()->get('requestManager')->getGET();
            $params['POST']         = $this->getDI()->get('requestManager')->getPOST();
            $params['FILES']        = $this->getDI()->get('requestManager')->getFILES();
            
            $uiPageActionName       = "mainAction";
            
            if(isset($p_urlparams[3]) && method_exists($uiService, $p_urlparams[3] . "Action")){

                $uiPageActionName   = $p_urlparams[3] . "Action";
            }
            
            $this->getDI()->get("responseManager")->setHtml($uiService->doPageRender($uiPageActionName, $params));
            
        }else{

            //TODO: ERROR
        }
    }

    protected function loadUiService($p_serviceClass, $p_urlparams){
        
        $this->getDI()->get("responseManager")->setHeader("Content-Type", "application/json");

        if(class_exists($p_serviceClass)){

            $uiService              = new $p_serviceClass($this->getDI());
            
            $params = array();
            $params['URL']          = $p_urlparams;
            $params['GET']          = $this->getDI()->get('requestManager')->getGET();
            $params['JSON']         = $this->getDI()->get('requestManager')->getJSON();
            $params['FILES']        = $this->getDI()->get('requestManager')->getFILES();

            $uiServiceMethodName          = "dataService";

            if(isset($p_urlparams[2])){

                $methodName         = \lcfirst(\Phalcon\Text::camelize($p_urlparams[2]));
                
                $uiServiceMethodName      = $methodName . "Service";
            }

            if(method_exists($uiService, $uiServiceMethodName)){

                $uiService->doService($uiServiceMethodName, $params);

            }else{

                $this->getDI()->get("responseManager")->setError("Method " . $p_serviceClass . " " . $uiServiceMethodName . " Not Found!");
            }

        }else{

            $this->getDI()->get("responseManager")->setError("Service " . $p_serviceClass . " Not Found!");
        }
    }
}