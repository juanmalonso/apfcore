<?php
namespace Nubesys\Core\Utils;

use Phramework\ExpressionParser\ExpressionParser as EParser;
use Phramework\ExpressionParser\Language;

class Expressions
{

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

        $dot = new \Adbar\Dot($p_data);
        
        $expression = self::normalizeDataExpresion($dot->flatten(), $p_expression);
        
        return self::evaluate($expression);
    }

    public static function evaluate($p_expression){
        
        $result = false;
        
        if(is_array($p_expression) && count($p_expression) > 0){

            $language = new Language();

            $language->set('or',function (bool $l, bool $r) : bool {
                return $l || $r;
            });

            $language->set('and',function (bool $l, bool $r) : bool {
                return $l && $r;
            });

            $language->set('equal',function ($l, $r) : bool {
                return $l == $r;
            });

            $language->set('start',function ($l, $r) : bool {
                return (strpos($l, $r) === 0) ? true : false;
            });

            $language->set('in',function ($l, $r) : bool {
                
                return (in_array($l, \explode(",", $r))) ? true : false;
            });

            $language->set('+',function ($l, $r) : float {
                return $l + $r;
            });

            $language->set('-',function ($l, $r) : float {
                return $l - $r;
            });

            $language->set('*',function ($l, $r) : float {
                return $l * $r;
            });

            $language->set('/',function ($l, $r) : float {
                
                if($r == 0){

                    $r = 1;
                }

                return $l / $r;
            });

            $parser = new EParser($language);
        
            $result = $parser->evaluate($p_expression);
        }

        return $result;
    }
}