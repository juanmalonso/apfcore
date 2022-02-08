<?php

namespace Nubesys\Core\Ws\Tracking;

use Nubesys\Core\Services\WsService;

use Nubesys\Data\DataSource\DataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Objects as ObjectsDataSource;

class UrlTracking extends WsService {

    public function clickMethod(){

        if($this->hasUrlParam(6)){

            
            $this->tracking($this->getUrlParam(6));
            
        }else{

            $this->setServiceError("Tracking ID Not Found");
        }
    }

    public function qrscanMethod(){
        
        if($this->hasUrlParam(6)){

            $qrtrackingDataSourceOptions            = array();
            $qrtrackingDataSourceOptions["model"]   = "qrtracking";

            $qrtrackingDataSource                   = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $qrtrackingDataSourceOptions));

            $qrtrackingObjectData                   = $qrtrackingDataSource->getData($this->getUrlParam(6));
            
            if(isset($qrtrackingObjectData["tracking"])){
                
                $qrtrackingEventData                = array();
                $qrtrackingEventData['tracking']    = array("tracking" => $qrtrackingObjectData["tracking"]);
                $qrtrackingEventData['device']      = $this->getDI()->get('tracker')->addDeviceData();
                $qrtrackingEventData['request']     = $this->getDI()->get('tracker')->addRequestData();
                
                $this->trackEvent("QRSCAN", $qrtrackingEventData);

                $this->tracking($qrtrackingObjectData["tracking"]);
            }else{

                $this->setServiceError("QR Tracking DATA Not Found");
            }
        }else{

            $this->setServiceError("QR Tracking ID Not Found");
        }

        //$this->setServiceSuccess($result);
    }

    private function tracking($p_trackingId){

        $trackingDataSourceOptions              = array();
        $trackingDataSourceOptions["model"]     = "tracking";

        $trackingDataSource                     = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $trackingDataSourceOptions));

        $trackingObjectData                     = $trackingDataSource->getData($p_trackingId);

        if(isset($trackingObjectData["url"])){

            $trackingData                       = array();
            
            //TRACKING
            $trackingData['tracking']           = $trackingObjectData['_id'];
            $trackingData['campanha']           = $trackingObjectData['campanha'];
            $trackingData['afiliado']           = $trackingObjectData['afiliado'];
            $trackingData['tags']               = $trackingObjectData['tags'];

            //REPLACE VARS
            $trackingScope                      = array();
            $trackingScope["accid"]             = $this->getDI()->get('global')->get('global.accid');
            $trackingScope["sesid"]             = $this->getDI()->get('session')->getId();
            $trackingScope['trkid']             = $trackingData['tracking'];
            $trackingScope['cmpid']             = $trackingData['campanha'];
            $trackingScope['affid']             = $trackingData['afiliado'];

            $url                                = $this->getDI()->get('tracker')->replaceTrackingVars($trackingObjectData["url"], $trackingScope, array("sesid", "trkid"));
            
            $url                                = $this->getDI()->get('tracker')->replaceAdditionalVars($url, $this->allGetParams());
            
            $trackingData['url']                = $url;
            
            //DEDIRECTION
            $this->redirectToAndContinue($url);
            
            $trackingEventData                  = array();
            $trackingEventData['tracking']      = $trackingData;
            $trackingEventData['device']        = $this->getDI()->get('tracker')->addDeviceData();
            $trackingEventData['request']       = $this->getDI()->get('tracker')->addRequestData();
            
            $this->trackEvent("CLICK", $trackingEventData);
        }else{

            $this->setServiceError("Invalid Tracking Data");
        }
    }

    private function redirectToAndContinue($p_url){

        header("Location: $p_url");
        
        //Erase the output buffer
        if(ob_get_length()){
            
            ob_end_clean();
        }

        //Tell the browser that the connection's closed
        header("Connection: close");

        //Ignore the user's abort (which we caused with the redirect).
        ignore_user_abort(true);
        //Extend time limit to 30 minutes
        set_time_limit(1800);
        //Extend memory limit to 10MB
        ini_set("memory_limit", "128M");
        //Start output buffering again
        ob_start();

        //Tell the browser we're serious... there's really
        //nothing else to receive from this page.
        header("Content-Length: 0");

        //Send the output buffer and turn output buffering off.
        ob_end_flush();
        flush();
        //Close the session.
        session_write_close();
    }
}