<?php
namespace Nubesys\Core\Utils;

class Struct
{
    
    public static function isValidJson($p_string){

        return ((is_string($p_string) && (is_object(json_decode($p_string)) || is_array(json_decode($p_string))))) ? true : false;
    }

    public static function toArray($p_object){
        
        return (array)json_decode(json_encode($p_object),true);
    }

    public static function toObject($p_object){
        
        return (object)json_decode(json_encode($p_object),false);
    }

    public static function flatten(array $arr, $prefix = '', $depth = 0, $adepth = 0)
    {
        $out = array();
        
        if(is_object($arr)){
            $arr = (array)$arr;
        }
        
        $adepth++;

        foreach ($arr as $k => $v) {

            $key = (!strlen($prefix)) ? $k : "{$prefix}.{$k}";

            if($adepth < $depth){
                
                if (is_array($v)) {
                    $out += self::flatten($v, $key, $depth, $adepth);
                } elseif(is_object($v)){
                    $out += self::flatten((array)$v,$key, $depth, $adepth);
                } else {
                    $out[$key] = $v;
                }
            }else{

                $out[$key] = $v;
            }
        }
        
        return $out;
    }

    public static function encodeFieldValue($p_value){
        
        if (is_string($p_value) && self::isValidJson($p_value)) {

            return self::toObject(json_decode($p_value));
        }elseif(is_string($p_value)){

            return $p_value;
        }else{

            return self::toObject($p_value);
        }
    }

    public static function extendFieldValues($p_valueA, $p_valueB, $p_asString = false){

        $result = false;

        if($p_asString){

            if(is_null($p_valueA) || strlen($p_valueA) == 0){

                $result = $p_valueB;
            }else{

                $result = $p_valueA;
            }
        }else {

            $objA = self::toArray(self::encodeFieldValue($p_valueA));

            $objB = self::toArray(self::encodeFieldValue($p_valueB));

            $result = self::extendArray($objA, $objB);

            $result = self::toObject($result);
        }

        return $result;
    }

    public static function extendArray(){

        $arrays = func_get_args();
        $base = array_shift($arrays);
        foreach ($arrays as $array) {
            reset($base);
            while (list($key, $value) = @each($array)) {

                if (is_array($value) && @is_array($base[$key])) {

                    $base[$key] = self::extendArray($base[$key], $value);
                } else {

                    $base[$key] = $value;
                }
            }
        }

        return $base;
    }

    public static function decodeUtf8($p_data){

        if(is_array($p_data)){

            foreach($p_data as $key => $value){

                if(is_string($value)){

                    $p_data[$key] = utf8_decode($value);
                }elseif(is_array($value) || is_object($value)){

                    $p_data[$key] = self::decodeUtf8($value);
                }
            }
        }elseif(is_object($p_data)){

            foreach($p_data as $key => $value){

                if(is_string($value)){

                    $p_data->$key = utf8_decode($value);
                }elseif(is_array($value) || is_object($value)){

                    $p_data->$key = self::decodeUtf8($value);
                }
            }
        }

        return $p_data;
    }

    public static function encodeUtf8($p_data){

        if(is_array($p_data)){

            foreach($p_data as $key => $value){

                if(is_string($value)){

                    $p_data[$key] = utf8_encode($value);
                }elseif(is_array($value) || is_object($value)){

                    $p_data[$key] = self::encodeUtf8($value);
                }
            }
        }elseif(is_object($p_data)){

            foreach($p_data as $key => $value){

                if(is_string($value)){

                    $p_data->$key = utf8_encode($value);
                }elseif(is_array($value) || is_object($value)){

                    $p_data->$key = self::encodeUtf8($value);
                }
            }
        }

        return $p_data;
    }

    public static function decodeJsonField($p_field, $p_value, $p_filter){

        $result = $p_value;

        if(in_array($p_field, $p_filter)){

            $result = json_decode(utf8_encode($p_value));
        }

        return $result;
    }
}