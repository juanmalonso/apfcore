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
            
            //$this->getDI()->get('sessionManager')->start($this->getDI()->get('global')->get('sesid'));
            
            $this->getDI()->get("responseManager")->setHtml($uiService->doPageRender($uiPageActionName, $params));

            //$this->getDI()->get('sessionManager')->end();

            //$this->getDI()->get("responseObject")->setCookie("NBSSESSID", $this->getDI()->get('sessionManager')->getId());
            
        }else{

            //TODO: ERROR
        }
    }

    protected function loadUiService($p_serviceClass, $p_urlparams){
        
        $serviceClass               = \urldecode($p_serviceClass);

        if(class_exists($serviceClass)){

            $uiService              = new $serviceClass($this->getDI());
            
            $params = array();
            $params['URL']          = $p_urlparams;
            $params['GET']          = $this->getDI()->get('swoolerequest')->get;
            $params['JSON']         = null;
            $params['FILES']        = $this->getDI()->get('swoolerequest')->files;

            if($this->getDI()->get('swoolerequest')->rawContent() != ""){
                
                if(\Nubesys\Core\Utils\Struct::isValidJson($this->getDI()->get('swoolerequest')->rawContent())){

                    $data['JSON']       = json_decode($this->getDI()->get('swoolerequest')->rawContent(),true);
                }
            }

            $uiServiceName          = "dataService";

            if(isset($p_urlparams[3]) && method_exists($uiService, $p_urlparams[3] . "Service")){

                $uiServiceName      = $p_urlparams[3] . "Service";
            }

            $this->getDI()->get("responseObject")->setHeader("Content-Type", "application/json");
            $this->getDI()->get("responseObject")->setHeader("x-nbs", "v6.0");

            $this->getDI()->get('sessionManager')->start($this->getDI()->get('global')->get('sesid'));
            
            $uiService->doService($uiServiceName, $params);

            $this->getDI()->get('sessionManager')->end();
        }else{

            //TODO: ERROR
        }
    }

    /*
    private function doApiFlow($p_urlparams, $p_schema){

        $this->getDI()->get('swooleresponse')->header("Content-Type", "application/json");

        $data = array();
        $data['URL']        = $p_urlparams;
        $data['GET']        = $this->getDI()->get('swoolerequest')->get;
        $data['FILES']      = $this->getDI()->get('swoolerequest')->files;
        $data['JSON']       = null;
        
        if(\Nubesys\System\Lib\Utils\Parse::isValidJson($this->getDI()->get('swoolerequest')->rawContent())){

            $data['JSON']   = json_decode($this->getDI()->get('swoolerequest')->rawContent(),true);
        }

        $result = NULL;

        $flow = new \Nubesys\Core\Flow\Flows\ApiFlow($this->getDI(), $p_schema, $result);

        $flow->start($data);

    }

    private function doUiFlow($p_urlparams, $p_schema){
        
        $this->getDI()->get('swooleresponse')->header("Content-Type", "text/html; charset=utf-8");

        $data = array();
        $data['URL']        = $p_urlparams;
        $data['GET']        = $this->getDI()->get('swoolerequest')->get;
        $data['POST']       = $this->getDI()->get('swoolerequest')->post;
        $data['FILES']      = $this->getDI()->get('swoolerequest')->files;
        
        $result = NULL;

        $flow = new \Nubesys\Core\Flow\Flows\UiFlow($this->getDI(), $p_schema, $result);

        $flow->start($data);
        
    }

    private function getFlowSchema($p_urlparams){
        $result = false;
        
        
        
        $moduleJsonPath         = $this->getModuleJsonPath($module);

        $path                   = $moduleJsonPath . "/flow/flows";

        $path                   .= "/" . $p_urlparams[0];

        $path                   .= "-" . $service . "-flow.json";
        
        if($this->getDI()->get('global')->get('schemasCache') == 'off'){

            $result = json_decode(file_get_contents($path));

            echo date("Y-m-d H:i:s") . " - FROM FILE \r\n";
        } else {

            if($this->getDI()->get('redisCache')->exists($path)){

                $result = $this->getDI()->get('redisCache')->get($path);
    
                echo date("Y-m-d H:i:s") . " - FROM CACHE \r\n";
            }else{
    
                if(file_exists($path)){
                    
                    $result = json_decode(file_get_contents($path));
    
                    $this->getDI()->get('redisCache')->save($path, $result, 3600);
    
                    echo date("Y-m-d H:i:s") . " - FROM FILE \r\n";
                }
            }
        }
        
        return $result;
    }

    private function getModuleJsonPath($p_module){
        $result = false;

        $pathPartes         = array("nubesys", $p_module);

        $moduleNamespace    = implode("\\",array_map(function ($e){ return \Phalcon\Text::camelize($e);}, $pathPartes));

        if(isset($this->getDI()->get('loader')->getNamespaces()[$moduleNamespace])){

            $result = dirname($this->getDI()->get('loader')->getNamespaces()[$moduleNamespace][0], 2) . "/json/" . $p_module;
        }$this->getDI()->get('sessionManager')->end();

        return $result;
    }
    */
}