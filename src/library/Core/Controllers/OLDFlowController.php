<?php
namespace Nubesys\Core\Controllers;

use Phalcon\Mvc\Controller;

class FlowController extends Controller
{
    
    public function routeAction(){
        
        $urlparams = \Nubesys\Core\Library\Utils\Parse::parseUrlParams($this->dispatcher->getParams());

        if(isset($urlparams[0]) && isset($urlparams[1]) && isset($urlparams[2])){

            //print_r($this->getDI()->get('swoolerequest'));

            $schema             = $this->getFlowSchema($urlparams);
            
            if($schema !== false){
                switch ($urlparams[0]) {
                    case 'api':
                        
                        $this->doApiFlow($urlparams, $schema);
                        
                        break;

                    case 'ui':
                        
                        $this->doUiFlow($urlparams, $schema);
                        
                        break;
                    
                    default:
                        # code...
                        break;
                }
            }else{

                //TODO : ERROR....
            }

        } else {
            
            //TODO : ERROR....
        }
    }

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
        
        $module                 = $p_urlparams[1];
        $service                = (isset($p_urlparams[2])) ? $p_urlparams[2] : $this->getDI()->get('swoolerequest')->server['request_method'] ;
        
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
        }

        return $result;
    }
}