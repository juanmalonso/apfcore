<?php
namespace Nubesys\Core\Utils;

class Url
{
    public static function parseUrlParams($p_params){

        $result = array();

        for($p = 0; $p < count($p_params); $p++){

            if(strpos($p_params[$p], ":") !== false){

                $partes = explode(":", $p_params[$p]);

                if(strpos($partes[1], ",") !== false){

                    $result[$partes[0]] = explode(",",$partes[1]);
                }else{

                    $result[$partes[0]] = $partes[1];

                }
            }else{

                $result[$p] = $p_params[$p];
            }
        }

        return $result;
    }
}