<?php
namespace Nubesys\Core\Controllers;

use Nubesys\Core\Controllers\Controller;

class CoreBinController extends Controller
{

    protected function getLoaderMethod($p_serviceTypeParam){
        $result = "loadWebService";

        return $result;
    }
    
    protected function loadWebService($p_serviceClass, $p_urlparams){
        
        if(class_exists($p_serviceClass)){
            
            $wsService              = new $p_serviceClass($this->getDI());
            
            $params = array();
            $params['URL']          = $p_urlparams;
            $params['GET']          = $this->getDI()->get('swoolerequest')->get;
            $data['JSON']           = null;
            
            if($this->getDI()->get('swoolerequest')->rawContent() != ""){
                
                if(\Nubesys\Core\Utils\Struct::isValidJson($this->getDI()->get('swoolerequest')->rawContent())){

                    $data['JSON']       = json_decode($this->getDI()->get('swoolerequest')->rawContent(),true);
                }
            }

            $wsServiceMethodName       = "getMethod";

            if(isset($p_urlparams[3]) && method_exists($wsService, $p_urlparams[3] . "Method")){

                $wsServiceMethodName   = $p_urlparams[3] . "Method";
            }else if(method_exists($wsService, strtolower($this->getDI()->get('swoolerequest')->server['request_method']) . "Method")){

                $wsServiceMethodName   = strtolower($this->getDI()->get('swoolerequest')->server['request_method']) . "Method";
            }
            
            $this->getDI()->get('sessionManager')->start($this->getDI()->get('global')->get('sesid'));
            
            $wsService->$wsServiceMethodName($params);

            $this->getDI()->get('sessionManager')->end();

            $this->getDI()->get("responseObject")->setHeader("x-nbssessid", $this->getDI()->get('sessionManager')->getId());
        }else{

            $this->getDI()->get("responseObject")->setError("Service " . $p_serviceClass . " Not Found!");
        }
    }
}