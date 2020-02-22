<?php
namespace Nubesys\Core;
//TODO Revisar common phalcon 4
use Phalcon\Mvc\User\Plugin;

class Common extends Plugin
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
    protected function cacheExists($p_cacher, $p_key){

        return $this->getDI()->get('cacher')->exists($p_cacher, $p_key);
    }

    protected function cacheGet($p_cacher, $p_key){

        return $this->getDI()->get('cacher')->get($p_cacher, $p_key);
    }

    protected function cacheSave($p_cacher, $p_key, $p_data, $p_lifetime = 3600){

        return $this->getDI()->get('cacher')->save($p_cacher, $p_key, $p_data, $p_lifetime);
    }

    //COMMON SESSION MANAGMENT
    protected function sessionGetId(){

        return $this->getDI()->get('sessionManager')->getId();
    }

    protected function sessionHas($p_key){

        return $this->getDI()->get('sessionManager')->has($p_key);
    }

    protected function sessionGet($p_key){

        return $this->getDI()->get('sessionManager')->get($p_key);
    }

    protected function sessionSet($p_key, $p_value){

        return $this->getDI()->get('sessionManager')->set($p_key, $p_value);
    }

}

?>
