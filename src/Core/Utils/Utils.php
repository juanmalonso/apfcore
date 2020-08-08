<?php
namespace Nubesys\Core\Utils;

class Utils
{
    
    public static function getGlobalValue($p_di, $p_key, $p_type = 'int'){

        $result = false;

        if(self::hasGlobalValue($p_di, $p_key)) {

            $type = $p_type;

            if ($p_type == 'jsono' || $p_type == 'jsona') {

                $type = 'json';
            }

            $dateResult = $p_di->get('utildb')->query("SELECT _$type AS val FROM system_keyvalues WHERE _key = '$p_key';");
            $dateResult = $dateResult->fetchAll($dateResult);

            if (count($dateResult) > 0) {

                if ($type == 'json') {

                    $result = json_decode(utf8_encode($dateResult[0]['val']));

                    if ($p_type == 'jsona') {

                        $result = \Nubesys\Platform\Util\Parse::toArray($result);
                    }

                } else {

                    $result = $dateResult[0]['val'];
                }
            }
        }

        return $result;
    }

    public static function hasGlobalValue($p_di, $p_key){

        $result = false;

        $dateResult = $p_di->get('utildb')->query("SELECT COUNT(*) AS cantidad FROM system_keyvalues WHERE _key = '$p_key';");
        $dateResult = $dateResult->fetchAll($dateResult);

        if(count($dateResult) > 0){

            if($dateResult[0]['cantidad'] > 0){

                $result = true;
            }
        }

        return $result;
    }

    public static function removeGlobalValue($p_di, $p_key){

        $result = true;

        if(self::hasGlobalValue($p_di, $p_key)){

            $result = $p_di->get('db')->delete('system_keyvalues', "_key = '$p_key'");
        }

        return $result;
    }

    public static function setGlobalValue($p_di, $p_key, $p_value, $p_type = 'int'){

        $result = false;

        $value = $p_value;
        $field = $p_type;

        switch ($p_type){

            case 'int' :
                $value = $p_value;
                break;
            case 'text' :
                $value = $p_value;
                break;
            case 'varchar' :
                $value = $p_value;
                break;
            case 'jsona' :
                $value = json_encode(\Nubesys\Platform\Util\Parse::encodeUtf8($p_value));
                $field  = 'json';
                break;
            case 'jsono' :
                $value = json_encode(\Nubesys\Platform\Util\Parse::encodeUtf8($p_value));
                $field  = 'json';
                break;
        }

        $data = array();

        if(self::hasGlobalValue($p_di, $p_key)){

            $data['_' . $field] = $value;

            $result = $p_di->get('utildb')->updateAsDict('system_keyvalues', $data, "_key = '$p_key'");
        }else{

            $data['_key']  = $p_key;
            $data['_' . $field] = $value;

            $result = $p_di->get('utildb')->insertAsDict('system_keyvalues', $data);
        }

        return $result;
    }
    
    public static function getDateTime($p_di){

        $result = false;

        $dateResult = $p_di->get('utildb')->query("SELECT NOW() AS time;");
        $dateResult = $dateResult->fetchAll($dateResult);

        if(count($dateResult) > 0){

            $result = $dateResult[0]['time'];
        }

        return $result;
    }
    
    public static function getWeekOfYearKey($p_di){

        $result = false;

        $dateResult = $p_di->get('utildb')->query("SELECT WEEKOFYEAR(NOW()) AS woykey;");
        $dateResult = $dateResult->fetchAll($dateResult);

        if(count($dateResult) > 0){

            $result = $dateResult[0]['woykey'];
        }

        return $result;
    }

    public static function getYearWeekKey($p_di){

        $result = false;

        $dateResult = $p_di->get('utildb')->query("SELECT YEARWEEK(NOW()) AS ywkey;");
        $dateResult = $dateResult->fetchAll($dateResult);

        if(count($dateResult) > 0){

            $result = $dateResult[0]['ywkey'];
        }

        return $result;
    }

    public static function getTimeStamp($p_di){

        /*$result = false;

        $dateResult = $p_di->get('db')->query("SELECT CONV(CONCAT(SUBSTRING(uid,16,3), SUBSTRING(uid,10,4), SUBSTRING(uid,1,8)) ,16,10) DIV 10000 - (141427 * 24 * 60 * 60 * 1000) AS time FROM (SELECT UUID() uid) AS alias;");
        $dateResult = $dateResult->fetchAll($dateResult);

        if(count($dateResult) > 0){

            $result = $dateResult[0]['time'];
        }

        return $result;*/

        return (int)str_replace('.','',(string)microtime(true));
    }

    public static function getPageSequence($p_di, $p_key, $p_size){

        $rowSequence = self::getSequenceCycleValue($p_di, $p_key . '_row', $p_size);

        if($rowSequence == 0){

            $result = self::getSequenceNextValue($p_di, $p_key);

            self::setGlobalValue($p_di, $p_key, $result);
        }else{

            $result = self::getGlobalValue($p_di, $p_key);
        }

        return $result;
    }

    public static function getSequenceNextValue($p_di, $p_sequence){

        $result = false;

        $dateResult = $p_di->get('utildb')->query("SELECT NEXTVALUE('$p_sequence',1,1) AS sequence;");
        $dateResult = $dateResult->fetchAll($dateResult);

        if(count($dateResult) > 0){

            $result = $dateResult[0]['sequence'];
        }

        return $result;
    }

    public static function getSequenceCycleValue($p_di, $p_sequence, $p_max = 9){

        $result = false;

        $dateResult = $p_di->get('utildb')->query("SELECT CYCLE('$p_sequence',$p_max) AS sequence;");
        $dateResult = $dateResult->fetchAll($dateResult);

        if(count($dateResult) > 0){

            $result = $dateResult[0]['sequence'];
        }

        return $result;
    }

    public static function getMemorySequenceCycleValue($p_di, $p_sequence, $p_max = 9){

        $seq = 0;
        
        if($p_di->get('cache')->has($p_sequence)){
            
            $seq = (int)$p_di->get('cache')->get($p_sequence);
        }else{
            
            $seq = 0;
        }
        
        if($seq < $p_max){
            
            $p_di->get('cache')->set($p_sequence, $seq + 1, 3600);
        }else{
            
            $p_di->get('cache')->set($p_sequence, 0, 3600);
        }
        
        return $seq;
    }

    public static function GUID($p_di)
    {

        $result = false;

        $dateResult = $p_di->get('utildb')->query("SELECT UUID() AS uuid;");
        $dateResult = $dateResult->fetchAll($dateResult);

        if(count($dateResult) > 0){

            $result = $dateResult[0]['uuid'];
        }

        return $result;

        /*if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
        */
    }

    public static function GUIDHash($p_di)
    {
        return md5(self::GUID($p_di));
    }

    public static function slugify($p_string){

        $result = $p_string;

        $table = array(
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', '/' => '-', ' ' => '-', '.' => '', '@' => '', '\'' => '-', '(' => '',
            ')' => '', 'º'=>'', '½'=>'', '&'=>'y', '?'=>'', '"' => ''
        );

        $result = \Phalcon\Text::underscore($p_string);

        $result = strtr($result, $table);

        return strtolower($result);
    }

    public static function decToBase($in, $to) {
        $in = (string) $in;
            $out = '';

        for ($i = strlen($in) - 1; $i >= 0; $i--) {
            $out = base_convert(bcmod($in, $to), 10, $to) . $out;
            $in = bcdiv($in, $to);
        }

        return preg_replace('/^0+/', '', $out);
    }

    public static function baseToDec($in, $from) {
        $in = (string) $in;
        $out = '';

        for ($i = 0, $l = strlen($in); $i < $l; $i++) {
            $x = base_convert(substr($in, $i, 1), $from, 10);
            $out = bcadd(bcmul($out, $from), $x);
        }

        return preg_replace('/^0+/', '', $out);
    }

    public static function getU36($p_di) {

        $result = '';

        $timestamp  = self::getTimeStamp($p_di);
        $sequence   = self::getMemorySequenceCycleValue($p_di, 'U36Digit', 9);
        $base36     = self::decToBase($timestamp + random_int(0, 999),36) . $sequence;

        //$p_di->get('logger')->debug("U36 $timestamp, $sequence, $base36", 'U36');

        return $base36;
    }

    public static function getCrc32($p_str) {

        return crc32($p_str);
    }

    public static function jwtGenerate($p_secret, $p_data, $p_live = 1){
        
        $time                   = time();

        $tokenObject            = array();
        $tokenObject['iat']     = $time;
        $tokenObject['exp']     = $time + (60*$p_live);
        $tokenObject['data']    = $p_data;
        
        return \Firebase\JWT\JWT::encode($tokenObject, $p_secret);
    }

    public static function jwtValidate($p_secret, $p_data, $p_token){
        
        $result = false;

        try {
            
            $tokenResult = \Firebase\JWT\JWT::decode($p_token, $p_secret, array('HS256'));
            
            if(is_object($tokenResult) && property_exists($tokenResult, "data")){

                foreach($tokenResult->data as $key=>$value){
                    
                    if($tokenResult->data->$key != $p_data[$key]){
                        
                        $result = false;
                        break;
                    }
                }

                $tokenResult->timeleft = $tokenResult->exp - time();
                
                $result = $tokenResult;
            }
        } catch (\Throwable $th) {
            
            //var_dump($th);
            //TODO: throw $th;
        }

        return $result;
    }

    //TODO V1, agregar opciones de pin exadecimal o base 36
    public static function generatePIN($digits = 4){
        $i = 0; //counter
        $pin = ""; //our default pin is blank.
        while($i < $digits){
            //generate a random number between 0 and 9.
            $pin .= self::decToBase(mt_rand(0, 34),36);
            $i++;
        }
        return strtoupper($pin);
    }
}