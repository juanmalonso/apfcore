<?php

namespace Nubesys\Core\Services;

use Nubesys\Core\AppCommon;

class Service extends AppCommon {

    protected $id;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->generateId();

        //GLOBAL SERVICE INSTANCE
        $this->getDI()->get('global')->set('service', $this);

        //INITIAL GLOBAL VARS
        $this->setGlobal("serviceId", $this->getId());
        $this->setGlobal("urlbase", $this->getDI()->get('config')->main->url->base);
        $this->setGlobal("appid", $this->getDI()->get('config')->main->id);
    }

    //ID
    public function getId(){

        return $this->id;
    }

    //LOCALSCOPE
    protected function hasLocal($p_key){

        $globalScopeKey     = "local." . $this->getId() . ".scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->has($p_key);
        }else{

            return $this->getScope($globalScopeKey)->hasDot($p_key);
        }
    }

    protected function getLocal($p_key){

        $globalScopeKey     = "local." . $this->getId() . ".scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->get($p_key);
        }else{

            return $this->getScope($globalScopeKey)->getDot($p_key);
        }
    }

    protected function allLocal(){

        $globalScopeKey     = "local." . $this->getId() . ".scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->all();
    }

    protected function setLocal($p_key, $p_value){

        $globalScopeKey     = "local." . $this->getId() . ".scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->set($p_key, $p_value);
        }else{

            return $this->getScope($globalScopeKey)->setDot($p_key, $p_value);
        }
    }

    protected function setAllLocals($p_values){
        
        foreach($p_values as $key=>$value){
            
            $this->setLocal($key, $value);
        }
    }

    //PARAMS
    protected function setParams($p_params){
        
        foreach($p_params as $key=>$value){

            switch ($key) {
                case 'URL':
                    $this->setAllUrlParams($value);
                    break;

                case 'GET':
                    $this->setAllGetParams($value);
                    break;

                case 'POST':
                    $this->setAllPostParams($value);
                    break;

                case 'FILES':
                    $this->setAllFilesParams($value);
                    break;
                
                case 'JSON':
                    $this->setJsonParam($value);
                    break;

                default:
                    break;
            }
        }

        $this->setAllHeaders($this->getDI()->get('requestManager')->getHeaders());
    }

    public function doService($p_uiServiceName, $p_params){
        
        $this->setParams($p_params);

        $this->loadJsonTree();

        if(method_exists($this, "doCheckAuthorization")){
            
            if($this->doCheckAuthorization()){

                if(method_exists($this, $p_uiServiceName)){

                    $this->$p_uiServiceName();
                }
            }
        }else{

            if(method_exists($this, $p_uiServiceName)){

                $this->$p_uiServiceName();
            }
        }

        return 0;
    }

    //SERVICES (AJAX)
    protected function setServiceStatus($p_status){

        $this->getDI()->get("responseManager")->setStatus($p_status);
    }

    public function setServiceInfo($p_info){

        $this->getDI()->get("responseManager")->setInfo($p_info);
    }

    public function setServiceData($p_data){

        $this->getDI()->get("responseManager")->setData($p_data);
    }

    public function setServiceDebug($p_debug){

        $this->getDI()->get("responseManager")->setDebug($p_debug);
    }

    public function setServiceError($p_message){

        $this->getDI()->get("responseManager")->setError($p_message);
    }

    public function setServiceSuccess($p_data){

        $this->getDI()->get("responseManager")->setSuccess($p_data);
    }
}