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
        
        if(class_exists($p_serviceClass)){

            $uiService              = new $p_serviceClass($this->getDI());
            
            $params = array();
            $params['URL']          = $p_urlparams;
            $params['GET']          = $this->getDI()->get('requestManager')->getGET();
            $params['JSON']         = $this->getDI()->get('requestManager')->getPOST();
            $params['FILES']        = $this->getDI()->get('requestManager')->getFILES();

            $uiServiceName          = "dataService";

            if(isset($p_urlparams[3]) && method_exists($uiService, $p_urlparams[3] . "Service")){

                $uiServiceName      = $p_urlparams[3] . "Service";
            }

            $this->getDI()->get("responseManager")->setHeader("Content-Type", "application/json");

            //$this->getDI()->get('sessionManager')->start($this->getDI()->get('global')->get('sesid'));
            
            $uiService->doService($uiServiceName, $params);

            //$this->getDI()->get('sessionManager')->end();
        }else{

            //TODO: ERROR
        }
    }
}