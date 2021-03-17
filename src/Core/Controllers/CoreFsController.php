<?php
namespace Nubesys\Core\Controllers;

use Nubesys\Core\Controllers\Controller;

class CoreFsController extends Controller
{

    protected function getLoaderMethod($p_serviceTypeParam){
        $result = "loadFsService";

        return $result;
    }
    
    protected function loadFsService($p_serviceClass, $p_urlparams){
        
        if(class_exists($p_serviceClass)){

            $uiService              = new $p_serviceClass($this->getDI());
            
            $params = array();
            $params['URL']          = $p_urlparams;
            $params['GET']          = $this->getDI()->get('requestManager')->getGET();

            $uiService->doDownloadFile($params);

        }else{

            //TODO: ERROR
        }
    }
}