<?php
namespace Nubesys\Core\Http;

use Nubesys\Core\Common;

class HttpClient extends Common
{
    public function __construct($p_di){

        $this->setDI($p_di);
    }

    public function httpGet($p_url, $p_getParams = array()){

        //TODO
    }

    public function httpJsonPost($p_url, $p_postParam = null, $p_getParams = array()){
        $result             = false;
        $client             = new \GuzzleHttp\Client();
        
        $response           = $client->post($p_url, [
            'json'      => $p_postParam,
            'verify'    => false
        ]);
        
        if($response->getStatusCode() == 200){
            
            if($response->hasHeader('Content-Length') && $response->getHeader('Content-Length')[0] > 1){
                
                $result     = json_decode($response->getBody()->getContents());
            }else{

                //TODO Error de http CLIENT
            }
        }else{

            $this->toStdOut("HTTP ERROR", $response->getStatusCode() . " - " . $response->getReasonPhrase());
        }

        return $result;
    }

    private function toStdOut($p_title, $p_message){

        $stdout = fopen('php://stdout', 'w');
        fputs($stdout, $p_title . ": \r\n" . $p_message ."\r\n");
        fclose($stdout);
    }
}