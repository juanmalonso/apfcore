<?php
namespace Nubesys\Core;

use Phalcon\Di\Injectable;

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
    protected function hasCahce($p_key){

        return $this->getDI()->get('cache')->has($p_key);
    }

    protected function getCache($p_cacher, $p_key){

        return $this->getDI()->get('cacher')->get($p_key);
    }

    protected function setCache($p_key, $p_data, $p_lifetime = 3600){

        return $this->getDI()->get('cache')->set($p_key, $p_data, $p_lifetime);
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

    //GLOBAL SCOPE
    protected function hasGlobal($p_key){

        return $this->getDI()->get('global')->has('global.' . $p_key);
    }

    protected function getGlobal($p_key){

        return $this->getDI()->get('global')->get('global.' . $p_key);
    }

    protected function getAllGlobal($p_key){

        return $this->getDI()->get('global')->getByKeyStartAt('global.');
    }

    protected function setGlobal($p_key, $p_value){

        $this->getDI()->get('global')->set('global.' . $p_key, $p_value);
    }

    //GET PARAM SCOPE
    protected function hasGetParam($p_key){

        return $this->getDI()->get('global')->has('params.get.' . $p_key);
    }

    protected function getGetParam($p_key){

        return $this->getDI()->get('global')->get('params.get.' . $p_key);
    }

    protected function getAllGetParams(){

        return $this->getParams("get");
    }

    //URL PARAM SCOPE
    protected function hasUrlParam($p_key){

        return $this->getDI()->get('global')->has('params.url.' . $p_key);
    }

    protected function getUrlParam($p_key){

        return $this->getDI()->get('global')->get('params.url.' . $p_key);
    }

    protected function getAllUrlParams(){

        return $this->getParams("url");
    }

    //POST PARAM SCOPE
    protected function hasPostParam($p_key){

        return $this->getDI()->get('global')->has('params.post.' . $p_key);
    }

    protected function getPostParam($p_key){

        return $this->getDI()->get('global')->get('params.post.' . $p_key);
    }

    protected function getAllPostParams(){

        return $this->getParams("post");
    }

    //FILES PARAM SCOPE
    protected function hasFileParam($p_key){

        return $this->getDI()->get('global')->has('params.files.' . $p_key);
    }

    protected function getFileParam($p_key){

        return $this->getDI()->get('global')->get('params.files.' . $p_key);
    }

    protected function getAllFileParams(){

        return $this->getParams("files");
    }

    //JSON PARAM SCOPE
    protected function hasJsonParam(){

        return $this->getDI()->get('global')->get('params.json');
    }

    protected function getJsonParam(){

        return $this->getDI()->get('global')->get('params.json');
    }

    protected function setJsonParam($p_value){

        $this->getDI()->get('global')->set('params.json', $p_value);
    }

    //TODO : VER FUNCIONES de manejo de files buscar por nombre, has por nombre etc

    //SET PARAM BY GROUP
    protected function setParamByGroup($p_group, $p_key, $p_value){

        $this->getDI()->get('global')->set('params.' . $p_group . '.' . $p_key, $p_value);
    }

    //SET PARAMS BY GROUP
    protected function setParamsByGroup($p_group, $p_values){
        
        foreach($p_values as $key=>$value){

            $this->getDI()->get('global')->set('params.' . $p_group . '.' . $key, $value);
        }
    }

    //GET PARAMS BY GROUP
    protected function getParams($p_group){

        return $this->getDI()->get('global')->getByKeyPrefix('params.' . $p_group . '.');
    }

    protected function getAllParams(){

        return $this->getDI()->get('global')->getByKeyPrefix('params.');
    }

}

?>
