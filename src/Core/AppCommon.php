<?php
namespace Nubesys\Core;

use Nubesys\Core\Common;

class AppCommon extends Common
{
    public function __construct($p_di){

        parent::__construct($p_di);
    }

    //COMMON SESSION MANAGMENT
    protected function getSessionId(){

        return $this->getDI()->get('session')->getId();
    }

    protected function hasSession($p_key){

        $sessionScopeKey     = "session.scope";

        $this->initScope($sessionScopeKey, $_SESSION);

        if(!strpos($p_key, '.')){

            return $this->getScope($sessionScopeKey)->has($p_key);
        }else{

            return $this->getScope($sessionScopeKey)->hasDot($p_key);
        }

        //return $this->getDI()->get('session')->has($p_key);
    }

    protected function getSession($p_key){

        $sessionScopeKey     = "session.scope";

        $this->initScope($sessionScopeKey, $_SESSION);

        if(!strpos($p_key, '.')){
            
            return $this->getScope($sessionScopeKey)->get($p_key);
        }else{
            
            return $this->getScope($sessionScopeKey)->getDot($p_key);
        }

        //return $this->getDI()->get('session')->get($p_key);
    }

    protected function allSession(){

        $sessionScopeKey     = "session.scope";

        $this->initScope($sessionScopeKey, $_SESSION);

        return $this->getScope($sessionScopeKey)->all();
    }

    protected function setSession($p_key, $p_value){
        
        $result = $this->getDI()->get('session')->set($p_key, $p_value);

        $sessionScopeKey     = "session.scope";

        $this->initScope($sessionScopeKey, $_SESSION);
        
        $this->getScope($sessionScopeKey)->set($p_key, $p_value);
        
        return $result;
    }

    protected function destroySession(){

        $result = $this->getDI()->get('session')->destroy();

        $sessionScopeKey     = "session.scope";

        $this->initScope($sessionScopeKey, $_SESSION);

        $this->getScope($sessionScopeKey)->setAll(array());

        return $result;
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

    protected function initScope($p_key, $p_data = array()){

        if(!$this->hasScope($p_key)){

            $scope = new Register();

            $scope->setAll($p_data);

            $this->setScope($p_key, $scope);
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

    protected function getJsonParam($p_key = null){

        $globalScopeKey     = "json.scope";

        if($p_key != null){

            if(isset(($this->getDI()->get('global')->get($globalScopeKey))[$p_key])){

                return ($this->getDI()->get('global')->get($globalScopeKey))[$p_key];
            }else{

                return null;
            }
        }else{

            return $this->getDI()->get('global')->get($globalScopeKey);
        }
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
}

?>