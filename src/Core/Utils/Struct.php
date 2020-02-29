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

        return (object)json_decode(json_encode($p_object));
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

    public static function normalizeDataExpresion($p_data, $p_expression){
        
        $result = array();
        foreach($p_expression as $expart){
            
            if(is_array($expart)){

                $result[] = self::normalizeDataExpresion($p_data, $expart);
            } else {

                $matches = array();
                preg_match('/^\$\{(.*)\}$/', $expart ,$matches);
                
                if(isset($matches[1])){

                    $result[] = $p_data[$matches[1]];
                }else{

                    $result[] = $expart;
                }
            }
        }

        return $result;
    }

    public static function evaluateDataExpression($p_data, $p_expression){
        
        $expression = self::normalizeDataExpresion(self::flatten($p_data), $p_expression);
        
        return \Nubesys\Core\Library\Utils\ExpressionParser::evaluate($expression);
    }
}