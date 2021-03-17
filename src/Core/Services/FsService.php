<?php

namespace Nubesys\Core\Services;

use Nubesys\Core\Services\Service;

class FsService extends Service {

    public function __construct($p_di)
    {
        parent::__construct($p_di);
    }

    protected function generateId(){

        $pathPartes     = explode("\\", $this->getClassPath());

        $className      = strtolower(array_pop($pathPartes));

        $this->id = implode("-", array_map(function ($e){ return \Phalcon\Text::uncamelize($e);}, $pathPartes)) . "-" . $className;
    }

    public function doDownloadFile($p_params){
        
        $this->setParams($p_params);

        $this->loadJsonTree();
        
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