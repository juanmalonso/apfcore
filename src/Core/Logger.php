<?php
namespace Nubesys\Core;

use Phalcon\Di\Injectable;

class Logger extends Injectable {
    //put your code here
    private $logger;

    private $types;
    private $leveltypes;
    private $context;

    public function __construct($p_logger, $p_types, $p_context) {
        
        $this->logger               = $p_logger;
        $this->leveltypes           = $p_types;
        $this->context              = $p_context;
    }

    private function log($p_type, $p_msg, $p_context, $p_data){
        
        if(in_array($p_type, $this->leveltypes)){
            
            $this->logToStream($p_type, $p_msg, $p_context, $p_data);
        }
    }

    private function logToStream($p_type, $p_msg, $p_context, $p_data){
        //var_dump($p_data);exit();
        $datetime                       = strftime("%Y-%m-%dT%H:%M:%S%z");
        $timestamp                      = microtime(true);

        $server                         = $this->getDI()->get('global')->get('global.server');
        $accid                          = $this->getDI()->get('global')->get('global.accid');
        $sesid                          = $this->getDI()->get('global')->get('global.sesid');

        $contexts                       = explode("|", $this->context);

        if(is_string($p_context) && strlen($p_context) > 0){

            $contexts                   = array_merge($contexts,explode("|", $p_context));
        }

        $logobj                         = array();
        $logobj['datetime']             = "$datetime";
        $logobj['timestamp']            = "$timestamp";
        $logobj['accid']                = $accid;
        $logobj['sesid']                = $sesid;
        $logobj['server']               = $server;
        $logobj['type']                 = "APPLOG";
        $logobj['contexts']             = implode(" ",$contexts);
        $logobj['message']              = strtoupper($p_type) . " " . $p_msg;

        if(is_string($p_data)){

            $logobj['info']             = $p_data; 
        }else if(is_object($p_data) || is_array($p_data)){

            $serialized                 = serialize($p_data);

            if(strlen($serialized) > 4600){

                //$logobj['serialized']   =  base64_encode($serialized);
            }else{

                foreach($p_data as $key=>$value){

                    if(!is_object($value) && !is_array($value)){

                        $logobj['info_' . $key] = $value;
                    }
                }
            }
        }
        
        $this->logger->$p_type(json_encode($logobj, JSON_UNESCAPED_SLASHES));
    }

    public function info($p_msg, $p_context = NULL, $p_data = NULL){

        $this->log('info', $p_msg, $p_context, $p_data);
    }

    public function emergency($p_msg, $p_context = NULL, $p_data = NULL){

        $this->log('emergency', $p_msg, $p_context, $p_data);
    }

    public function critical($p_msg, $p_context = NULL, $p_data = NULL){

        $this->log('critical', $p_msg, $p_context, $p_data);
    }

    public function debug($p_msg, $p_context = NULL, $p_data = NULL){
        
        $this->log('debug', $p_msg, $p_context, $p_data);
    }

    public function notice($p_msg, $p_context = NULL, $p_data = NULL){

        $this->log('notice', $p_msg, $p_context, $p_data);
    }

    public function warning($p_msg, $p_context = NULL, $p_data = NULL){

        $this->log('warning', $p_msg, $p_context, $p_data);
    }

    public function error($p_msg, $p_context = NULL, $p_data = NULL){

        $this->log('error', $p_msg, $p_context, $p_data);
    }

    public function alert($p_msg, $p_context = NULL, $p_data = NULL){

        $this->log('alert', $p_msg, $p_context, $p_data);
    }

    /**
     * @param mixed $accid
     */
    public function setAccid($p_accid)
    {
        $this->accid = $p_accid;
    }

    /**
     * @param string $server
     */
    public function setServer($p_server)
    {
        $this->server = $p_server;
    }

    /**
     * @param mixed $sesid
     */
    public function setSesid($p_sesid)
    {
        $this->sesid = $p_sesid;
    }

}