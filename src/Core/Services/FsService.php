<?php

namespace Nubesys\Core\Services;

use Nubesys\Core\Services\Service;

class FsService extends Service {

    public function __construct($p_di)
    {
        parent::__construct($p_di);
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

                default:
                    break;
            }
        }
    }

    public function doService($p_params){
        
        $this->setParams($p_params);
        
        if(method_exists($this, 'main')){

            $this->main();
        }

        return 0;
    }

    public function setServiceResponseFile($p_name, $p_typemime, $p_data){

        $this->getDI()->get('responseManager')->setHeader("Content-type", $p_typemime);
        $this->getDI()->get('responseManager')->setHeader("Content-Length", strlen($p_data));
        $this->getDI()->get('responseManager')->setHeader("Content-Disposition", 'attachment; filename="'.$p_name.'"');

        $this->getDI()->get('responseManager')->setData($p_data);
    }
}