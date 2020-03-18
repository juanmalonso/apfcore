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
    

    //COMMON CACHE MANAGMENT
    protected function hasCache($p_key){

        return $this->getDI()->get('cache')->has($p_key);
    }

    protected function getCache($p_key){

        return $this->getDI()->get('cache')->get($p_key);
    }

    protected function setCache($p_key, $p_data, $p_lifetime = 3600){

        return $this->getDI()->get('cache')->set($p_key, $p_data, $p_lifetime);
    }

    protected function deleteCache($p_key){

        return $this->getDI()->get('cache')->delete($p_key);
    }

    //COMMON SESSION MANAGMENT
    protected function getSessionId(){

        return $this->getDI()->get('session')->getId();
    }

    protected function hasSession($p_key){

        return $this->getDI()->get('session')->has($p_key);
    }

    protected function getSession($p_key){

        return $this->replaceValues($this->getDI()->get('session')->get($p_key));
    }

    protected function setSession($p_key, $p_value){

        return $this->getDI()->get('session')->set($p_key, $p_value);
    }
    //SCOPES
    protected function hasScope($p_key){

        return $this->getDI()->get('global')->has($p_key);
    }

    protected function getScope($p_key){

        return $this->getDI()->get('global')->get($p_key);
    }

    protected function setScope($p_key, $p_value){

        $this->getDI()->get('global')->set($p_key, $p_value);
    }

    protected function initScope($p_key){

        if(!$this->hasScope($p_key)){

            $this->setScope($p_key, new Register());
        }
    }

    //GLOBAL SCOPE
    
    protected function hasGlobal($p_key){

        $globalScopeKey     = "global.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->has($p_key);
    }

    protected function getGlobal($p_key){

        $globalScopeKey     = "global.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->get($p_key);
    }

    protected function allGlobal(){

        $globalScopeKey     = "global.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->all();
    }

    protected function setGlobal($p_key, $p_value){

        $globalScopeKey     = "global.scope";

        $this->initScope($globalScopeKey);

        $this->getScope($globalScopeKey)->set($p_key, $p_value);
    }

    //GET PARAM SCOPE
    protected function hasGetParam($p_key){
        
        $globalScopeKey     = "get.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->has($p_key);
    }

    protected function getGetParam($p_key){

        $globalScopeKey     = "get.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->get($p_key);
    }

    protected function allGetParams(){

        $globalScopeKey     = "get.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->all($p_key);
    }

    protected function setGetParam($p_key, $p_value){

        $globalScopeKey     = "get.scope";

        $this->initScope($globalScopeKey);

        $this->getScope($globalScopeKey)->set($p_key, $p_value);
    }

    protected function setAllGetParams($p_values){
        
        foreach($p_values as $key=>$value){
            
            $this->setGetParam($key, $value);
        }
    }

    //URL PARAM SCOPE
    protected function hasUrlParam($p_key){
        
        $globalScopeKey     = "url.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->has($p_key);
    }

    protected function getUrlParam($p_key){

        $globalScopeKey     = "url.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->get($p_key);
    }

    protected function allUrlParams(){

        $globalScopeKey     = "url.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->all($p_key);
    }

    protected function setUrlParam($p_key, $p_value){

        $globalScopeKey     = "url.scope";

        $this->initScope($globalScopeKey);

        $this->getScope($globalScopeKey)->set($p_key, $p_value);
    }

    protected function setAllUrlParams($p_values){
        
        foreach($p_values as $key=>$value){
            
            $this->setUrlParam($key, $value);
        }
    }

    //POST PARAM SCOPE
    protected function hasPostParam($p_key){
        
        $globalScopeKey     = "post.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->has($p_key);
    }

    protected function getPostParam($p_key){

        $globalScopeKey     = "post.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->get($p_key);
    }

    protected function allPostParams(){

        $globalScopeKey     = "post.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->all($p_key);
    }

    protected function setPostParam($p_key, $p_value){

        $globalScopeKey     = "post.scope";

        $this->initScope($globalScopeKey);

        $this->getScope($globalScopeKey)->set($p_key, $p_value);
    }

    protected function setAllPostParams($p_values){
        
        foreach($p_values as $key=>$value){
            
            $this->setPostParam($key, $value);
        }
    }

    //FILES PARAM SCOPE
    protected function hasFilesParam($p_key){
        
        $globalScopeKey     = "files.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->has($p_key);
    }

    protected function getFilesParam($p_key){

        $globalScopeKey     = "files.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->get($p_key);
    }

    protected function allFilesParams(){

        $globalScopeKey     = "files.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->all($p_key);
    }

    protected function setFilesParam($p_key, $p_value){

        $globalScopeKey     = "files.scope";

        $this->initScope($globalScopeKey);

        $this->getScope($globalScopeKey)->set($p_key, $p_value);
    }

    protected function setAllFilesParams($p_values){
        
        foreach($p_values as $key=>$value){
            
            $this->setFilesParam($key, $value);
        }
    }

    //JSON PARAM SCOPE
    protected function hasJsonParam(){

        $globalScopeKey     = "json.scope";

        return $this->getDI()->get('global')->hs($globalScopeKey);
    }

    protected function getJsonParam(){

        $globalScopeKey     = "json.scope";

        return $this->getDI()->get('global')->get($globalScopeKey);
    }

    protected function setJsonParam($p_value){

        $globalScopeKey     = "json.scope";

        $this->getDI()->get('global')->set($globalScopeKey, $p_value);
    }

    //DATA SOURCES
    protected function hasDataSource($p_key){
        $globalScopeKey     = "ds.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->has($p_key);
    }

    protected function getDataSource($p_key){

        $globalScopeKey     = "ds.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->get($p_key);
    }

    protected function setDataSource($p_key, $p_value){

        $globalScopeKey     = "ds.scope";

        $this->initScope($globalScopeKey);

        $this->getScope($globalScopeKey)->set($p_key, $p_value);
    }

    //JSON TREE
    protected function loadJsonTree($p_extraVars = array()){
        //TODO : Hacer cacheable los datos del JSON
        $path   = $this->classDir . "/" . $this->className . "_service.json";
        
        if(file_exists($path)){

            $serviceMainData             = json_decode(file_get_contents($path), true);
            
            //TODO : REPLACE VARS AQUI

            $this->setAllLocals($serviceMainData);
        }
    }

    protected function replaceValues($p_value){
        $result = $p_value;
        
        if(\is_string($p_value)){

            $pattern = '/^.*(v|f)\{(.*)\((.*)\)\}.*$/';
            
            $matches = array();

            if(\preg_match($pattern, $p_value, $matches)){

                if($matches[1] == "v"){

                    switch ($matches[2]) {
                        case 'session':
                            $result = $this->getSession($matches[3]);
                            break;

                        case 'global':
                            $result = $this->getGlobal($matches[3]);
                            break;

                        case 'service':
                            $result = $this->getService($matches[3]);
                            break;

                        case 'local':
                            $result = $this->getLocal($matches[3]);
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                }else if($matches[1] == "f"){

                    $method     = $matches[2];
                    $params     = \explode(",", $matches[3]);
                    
                    $result     = call_user_func_array(array($this, $method),$params);
                }
            }

            //RECURSIVE
            
        }else if(\is_array($p_value)){

            $result = array();
            
            foreach($p_value as $key=>$value){

                $result[$key] = $this->replaceValues($value);
            }
        }

        return $result;
    }
    
}

?>