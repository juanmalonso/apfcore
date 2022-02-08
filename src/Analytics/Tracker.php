<?php
namespace Nubesys\Analytics;

use Phalcon\Di\Injectable;

//DATA SOURCE
use Nubesys\Data\DataSource\DataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Objects as ObjectsDataSource;

class Tracker extends Injectable {

    private $options;

    public function __construct($p_options = array()) {
        
        $this->options                      = $p_options;
    }

    public function track($p_event, $p_data, $p_terms){
        
        $dataSourceOptions                  = array();
        $dataSourceOptions["model"]         = "events";

        $dataSource                         = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));

        $sesid                              = $this->getDI()->get('session')->getId();
        if($this->getDI()->get('global')->get('get.scope')->has('sesid')){

            $sesid                          = $this->getDI()->get('global')->get('get.scope')->get('sesid');
        }
        
        $trkid                              = "unknown";
        if($this->getDI()->get('global')->get('get.scope')->has('trkid')){

            $trkid                          = $this->getDI()->get('global')->get('get.scope')->get('trkid');

            $this->getDI()->get('session')->set("trkid", $trkid);

        }elseif($this->getDI()->get('session')->has("trkid")){

            $trkid                          = $this->getDI()->get('session')->get("trkid");
        }
        
        if($trkid != "unknown"){

            if(!isset($p_data["tracking"])){

                $p_data["tracking"]         = array("tracking" => $trkid);
            }
        }

        $eventsData                         = array();
        $eventsData["accid"]                = $this->getDI()->get('global')->get('global.accid');
        $eventsData["sesid"]                = $sesid;
        $eventsData["event"]                = $p_event;
        $eventsData["event_data"]           = $p_data;
        $eventsData["tags"]                 = $p_terms;
        
        return $dataSource->addData($eventsData);
    }

    public function addDeviceData($p_data = array()){

        if($this->getDI()->has("requestManager")){
            
            $p_data['browser']              = strtoupper($this->getDI()->get("requestManager")->getUserBrowser());
            $p_data['os']                   = strtoupper($this->getDI()->get("requestManager")->getUserOs());
            $p_data['device']               = strtoupper($this->getDI()->get("requestManager")->getUserDevice());
        }

        return $p_data;
    }

    public function addRequestData($p_data = array()){

        if($this->getDI()->has("requestManager")){
                
            $p_data['ua']                   = $this->getDI()->get("requestManager")->getUserAgent();
            $p_data['ip']                   = $this->getDI()->get("requestManager")->getClientAddress();
            $p_data['referer']              = $this->getDI()->get("requestManager")->getReferer();
            $p_data['uri']                  = $this->getDI()->get("requestManager")->getURI();
        }

        return $p_data;
    }

    public function replaceTrackingVars($p_url, $p_data, $p_mandatoriesParams = array()){

        $urlData                            = array();

        foreach($p_data as $key=>$value){

            $urlData['{' . $key . '}'] = $value;
        }

        $newUrl                             = "";

        $oldUrlComponents                   = parse_url($p_url);

        $newUrl                             .= $oldUrlComponents['scheme'] . "://";

        if(isset($oldUrlComponents['user'])){

            $newUrl                         .= $oldUrlComponents['user'];

            if(isset($oldUrlComponents['pass'])){

                $newUrl                     .= ":" . $oldUrlComponents['pass'];
            }

            $newUrl                         .= "@";
        }

        $newUrl                             .= $oldUrlComponents['host'];

        if(isset($oldUrlComponents['port'])){

            $newUrl                         .= ":" . $oldUrlComponents['port'];
        }

        if(isset($oldUrlComponents['path'])){

            $newUrl                         .= $oldUrlComponents['path'];
        }

        $queryStringComponents              = array();
        
        if(isset($oldUrlComponents['query'])){
            
            parse_str($oldUrlComponents['query'], $queryStringComponents);
        }

        foreach($p_mandatoriesParams as $mandatoryParam){

            $queryStringComponents[$mandatoryParam] = "{" . $mandatoryParam . "}";
        }

        $newUrl                             .= "?" . urldecode(http_build_query($queryStringComponents));
        
        if(isset($oldUrlComponents['fragment'])){

            $newUrl                         .= "#" . $oldUrlComponents['fragment'];
        }
        
        return $this->replaceUrl($newUrl, $urlData);
    }

    public function replaceAdditionalVars($p_url, $p_data){

        $urlData                            = array();

        foreach($p_data as $key=>$value){

            if($key != '_url' && $key != 'domain'){

                $urlData['%' . $key . '%'] = $value;
            }
        }

        return $this->replaceUrl($p_url, $urlData);
    }

    private function replaceUrl($p_url, $p_data){
        $result = $p_url;

        foreach($p_data as $key => $value){

            $result = str_replace($key, $value, $result);
        }

        return $result;
    }
}
