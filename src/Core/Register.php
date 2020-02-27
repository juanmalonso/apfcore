<?php
namespace Nubesys\Core;

use ArrayAccess;

class Register implements ArrayAccess
{
    private $container = array();

    public function __construct() {
        $this->container = array();
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    public function get($key){

        return isset($this->container[$key]) ? $this->container[$key] : null;
    }

    public function has($key){

        return isset($this->container[$key]) ? true : false;
    }

    public function remove($p_key){

        if($this->has($p_key)){

            unset($this->container[$p_key]);
        }
    }

    public function set($key, $value){

        if (is_null($key)) {
            $this->container[] = $value;
        } else {
            $this->container[$key] = $value;
        }
    }

    public function setAll($value){

        $this->container = $value;
    }

    public function all(){

        return $this->container;
    }

    public function strAppend($key, $value){

        if(!isset($this->container[$key])){

            $this->container[$key] = '';
        }

        if (is_null($key)) {
            $this->container[] = $value;
        } else {
            $this->container[$key] .= $value;
        }
    }

    public function keys(){

        return array_keys($this->container);
    }

    private function getByKeyStartAt($p_expression){

        $result = array();

        foreach($this->container as $key=>$value){

            if(strpos($key, $p_expression)){

                $result[$key] = $value;
            }
        }
        
        return $result;
    }
}