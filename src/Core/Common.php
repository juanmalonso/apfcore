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

    //COMMON SESSION MANAGMENT
    protected function getSessionId(){

        return $this->getDI()->get('session')->getId();
    }

    protected function hasSession($p_key){

        return $this->getDI()->get('session')->has($p_key);
    }

    protected function getSession($p_key){

        return $this->getDI()->get('session')->get($p_key);
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

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->has($p_key);
        }else{

            return $this->getScope($globalScopeKey)->hasDot($p_key);
        }
    }

    protected function getGlobal($p_key){

        $globalScopeKey     = "global.scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->get($p_key);
        }else{

            return $this->getScope($globalScopeKey)->getDot($p_key);
        }
    }

    protected function allGlobal(){

        $globalScopeKey     = "global.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->all();
    }

    protected function setGlobal($p_key, $p_value){

        $globalScopeKey     = "global.scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            $this->getScope($globalScopeKey)->set($p_key, $p_value);
        }else{

            $this->getScope($globalScopeKey)->setDot($p_key, $p_value);
        }
    }

    //GET PARAM SCOPE
    protected function hasGetParam($p_key){
        
        $globalScopeKey     = "get.scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->has($p_key);
        }else{

            return $this->getScope($globalScopeKey)->hasDot($p_key);
        }
    }

    protected function getGetParam($p_key){

        $globalScopeKey     = "get.scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->get($p_key);
        }else{

            return $this->getScope($globalScopeKey)->getDot($p_key);
        }
    }

    protected function allGetParams(){

        $globalScopeKey     = "get.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->all();
    }

    protected function setGetParam($p_key, $p_value){

        $globalScopeKey     = "get.scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            $this->getScope($globalScopeKey)->set($p_key, $p_value);
        }else{

            $this->getScope($globalScopeKey)->setDot($p_key, $p_value);
        }
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

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->has($p_key);
        }else{

            return $this->getScope($globalScopeKey)->hasDot($p_key);
        }
    }

    protected function getUrlParam($p_key){

        $globalScopeKey     = "url.scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->get($p_key);
        }else{

            return $this->getScope($globalScopeKey)->getDot($p_key);
        }
    }

    protected function allUrlParams(){

        $globalScopeKey     = "url.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->all();
    }

    protected function setUrlParam($p_key, $p_value){

        $globalScopeKey     = "url.scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            $this->getScope($globalScopeKey)->set($p_key, $p_value);
        }else{

            $this->getScope($globalScopeKey)->setDot($p_key, $p_value);
        }
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

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->has($p_key);
        }else{

            return $this->getScope($globalScopeKey)->hasDot($p_key);
        }
    }

    protected function getPostParam($p_key){

        $globalScopeKey     = "post.scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->get($p_key);
        }else{

            return $this->getScope($globalScopeKey)->getDot($p_key);
        }
    }

    protected function allPostParams(){

        $globalScopeKey     = "post.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->all();
    }

    protected function setPostParam($p_key, $p_value){

        $globalScopeKey     = "post.scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            $this->getScope($globalScopeKey)->set($p_key, $p_value);
        }else{

            $this->getScope($globalScopeKey)->setDot($p_key, $p_value);
        }
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

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->has($p_key);
        }else{

            return $this->getScope($globalScopeKey)->hasDot($p_key);
        }
    }

    protected function getFilesParam($p_key){

        $globalScopeKey     = "files.scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->get($p_key);
        }else{

            return $this->getScope($globalScopeKey)->getDot($p_key);
        }
    }

    protected function allFilesParams(){

        $globalScopeKey     = "files.scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->all();
    }

    protected function setFilesParam($p_key, $p_value){

        $globalScopeKey     = "files.scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            $this->getScope($globalScopeKey)->set($p_key, $p_value);
        }else{

            $this->getScope($globalScopeKey)->setDot($p_key, $p_value);
        }
    }

    protected function setAllFilesParams($p_values){
        
        foreach($p_values as $key=>$value){
            
            $this->setFilesParam($key, $value);
        }
    }

    //JSON PARAM SCOPE
    protected function hasJsonParam(){

        $globalScopeKey     = "json.scope";

        return $this->getDI()->get('global')->has($globalScopeKey);
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
            //var_dump($serviceMainData);

            $dot = new \Adbar\Dot($serviceMainData);

            foreach($dot->flatten() as $key=>$value){

                $this->setLocal($key, $this->parseBlocks($value));
            }
        }
    }

    protected function parseExpression($p_input){
    
        return "_expressionResult_";
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

        //FUNCTIONS MATCH
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

    protected function toObject($p_data){

        return \Nubesys\Core\Utils\Struct::toObject($p_data);
    }

    protected function toArray($p_data){

        return \Nubesys\Core\Utils\Struct::toArray($p_data);
    }
}

?>