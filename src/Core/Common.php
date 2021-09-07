<?php
namespace Nubesys\Core;

use Phalcon\Di\Injectable;
use Nubesys\Core\Register;

class Common extends Injectable
{
    
    protected $className;
    protected $classDir;
    
    public function __construct($p_di){

        $this->setDI($p_di);

        $this->className    = $this->getClassName();
        $this->classDir     = $this->getClassDir();
    }

    protected function linecho($msg){

        echo date("Y-m-d H:i:s") . " - " . $msg . " \r\n";
    }

    protected function getClassName(){
        $result = false;

        $classNamePartes = explode('\\', $this->getClassPath());

        if(strlen($classNamePartes[count($classNamePartes)-1]) > 0){

            $result = $classNamePartes[count($classNamePartes)-1];
        }else{

            $result = $classNamePartes[count($classNamePartes)-2];
        }

        return $result;
    }

    protected function getClassPath(){

        return get_class($this);
    }

    protected function getClassDir() {

        $rc = new \ReflectionClass(get_class($this));
        return dirname($rc->getFileName());
    }

    //COMMON LOG MANAGMENT
    protected function logCustom($p_msg, $p_context = NULL, $p_data = NULL){
       
        $this->getDI()->get('logger')->custom($p_msg, $p_context, $p_data);
    }

    protected function logInfo($p_msg, $p_context = NULL, $p_data = NULL){

        $this->getDI()->get('logger')->info($p_msg, $p_context, $p_data);
    }

    protected function logEmergency($p_msg, $p_context = NULL, $p_data = NULL){

        $this->getDI()->get('logger')->emergency($p_msg, $p_context, $p_data);
    }

    protected function logCritical($p_msg, $p_context = NULL, $p_data = NULL){

        $this->getDI()->get('logger')->critical($p_msg, $p_context, $p_data);
    }

    protected function logDebug($p_msg, $p_context = NULL, $p_data = NULL){
        
        $this->getDI()->get('logger')->debug($p_msg, $p_context, $p_data);
    }

    protected function logNotice($p_msg, $p_context = NULL, $p_data = NULL){

        $this->getDI()->get('logger')->notice($p_msg, $p_context, $p_data);
    }

    protected function logWarning($p_msg, $p_context = NULL, $p_data = NULL){

        $this->getDI()->get('logger')->warning($p_msg, $p_context, $p_data);
    }

    protected function logError($p_msg, $p_context = NULL, $p_data = NULL){

        $this->getDI()->get('logger')->error($p_msg, $p_context, $p_data);
    }

    protected function logAlert($p_msg, $p_context = NULL, $p_data = NULL){

        $this->getDI()->get('logger')->alert($p_msg, $p_context, $p_data);
    }

    //COMMON CACHE MANAGMENT
    protected function hasCache($p_key){
        
        return $this->getDI()->get('cache')->has($p_key);
    }

    protected function getCache($p_key, $p_default = null){
        
        return $this->getDI()->get('cache')->get($p_key, $p_default);
    }

    protected function setCache($p_key, $p_data, $p_lifetime = 3600){

        return $this->getDI()->get('cache')->set($p_key, $p_data, $p_lifetime);
    }

    protected function deleteCache($p_key){

        return $this->getDI()->get('cache')->delete($p_key);
    }

    protected function deleteMultipleCache($p_keys = array()){

        return $this->getDI()->get('cache')->deleteMultiple($p_keys);
    }

    protected function parseExpression($p_input){
        
        var_dump($p_input);exit();
        return var_dump($p_input);
    }
    
    protected function parseVariable($p_group, $p_name){
        
        switch ($p_group) {
                
            case 'global':
                $result = $this->getGlobal($p_name);
                break;
            
            case 'service':
                $result = $this->getService($p_name);
                break;
    
            case 'local':
                $result = $this->getLocal($p_name);
                break;
    
            case 'session':
                $result = $this->getSession($p_name);
                break;

            case 'url':
                $result = $this->getUrlParam($p_name);
                break;

            case 'get':
                $result = $this->getGetParam($p_name);
                break;

            case 'post':
                $result = $this->getPostParam($p_name);
                break;

            case 'allpost':
                $result = $this->allPostParams();
                break;

            case 'json':
                $result = $this->getJsonParam($p_name);
                break;
    
            default:
                $result = null;
                break;
        }
        
        return $result;
    }
    
    protected function parseFunction($p_name, $p_params){
        
        $params     = array();

        if(!is_null($p_params)){

            $params     = \explode(",", $p_params);
        }
        
        return      call_user_func_array(array($this, $p_name),$params);
    }
    
    protected function parseBlockValue($p_input, $p_forceTo = "any"){
        
        $result = $p_input;

        $matched                = false;

        //VARIABLES MATCH
        if(!$matched && preg_match('/^(var)\((.*):(.*)\)$/', $result, $matches)){

            $result     = $this->parseVariable($matches[2], $matches[3]);
            $matched    = true;
        }

        //FUNCTIONS MATCH
        if(!$matched && preg_match('/^(fun)\((.*):(.*)\)$/', $result, $matches)){
            
            $result     = $this->parseFunction($matches[2], $matches[3]);
            $matched    = true;
        }

        if(!$matched && preg_match('/^(fun)\((.*)\)$/', $result, $matches)){
            
            $result     = $this->parseFunction($matches[2], null);
            $matched    = true;
        }

        //EXPRESSIONS MATCH
        if(!$matched && preg_match('/^(exp)\((.*)\)$/', $result, $matches)){

            $result     = $this->parseExpression($matches[2]);
            $matched    = true;
        }
        
        if($p_forceTo == "string"){
            
            if(is_array($result)){
                
                $result = "_array_";
            }
            
            if(is_object($result)){
                
                $result = "_object_";
            }
            
            if(is_null($result)){
                
                $result = "_null_";
            }
        }
        
        return $result;
    }
    
    protected function parseBlocks($p_input){
        
        $result = $p_input;

        if(is_string($p_input)){
        
            $result = $p_input;
        
            $blockPattern     = '/^.*\{(.*)\}.*$/';
        
            while(preg_match($blockPattern, $result, $matches)){
            
                if("{" . $matches[1] . "}" == $matches[0]){
                
                    $result = $this->parseBlockValue($matches[1]);
                }else{
                
                    $result = str_replace("{" . $matches[1] . "}", $this->parseBlockValue($matches[1], "string"), $matches[0]);   
                }
            }
        
            $result = $this->parseBlockValue($result);
            
        } else if(is_array($p_input)){
        
            $result = array();
            
            foreach($p_input as $key=>$value){
                
                $result[$key] = $this->parseBlocks($value);
            }
        }
        
        return $result;
    }

    protected function parseData($p_data){
        
        $result = new \Adbar\Dot();
        $dot    = new \Adbar\Dot($p_data);
        
        foreach($dot->flatten() as $key=>$value){
            
            $value = str_replace("___var", "var", $value);
            $value = str_replace("___fun", "fun", $value);
            $value = str_replace("___exp", "exp", $value);

            $result->add($key, $this->parseBlocks($value));
        }
        
        return \json_decode($result->toJson());
    }

    protected function toObject($p_data){

        return \Nubesys\Core\Utils\Struct::toObject($p_data);
    }

    protected function toArray($p_data){

        return \Nubesys\Core\Utils\Struct::toArray($p_data);
    }
}

?>