<?php
namespace Nubesys\Core\Ui\Components\Modules;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class ModuleComponent extends VueUiComponent {

    public function mainAction(){

        $this->addJsSource("https://cdn.jsdelivr.net/burry.js/0.1.0/burry.js");
        
        if($this->hasLocal("referenceName")){

            $this->registerReference($this->getLocal("referenceName"));
        }
        
        $this->setDataSercvice();

        $this->setJsDataVar("dataScopeRegister", []);
        
    }

    protected function setDataSercvice(){
        
        $dataService                            = array();
        $dataService['name']                    = "";
        $dataService['type']                    = "service";
        $dataService['scope']                   = "service";
        $dataService['emitter']                 = "";
        $dataService['params']                  = array();

        if(method_exists($this,"getInitialDataService")){

            $dataService                        = $this->getInitialDataService($dataService);
        }

        if($this->hasLocal("dataService")){
            
            //OVERWRITE INITIAL DATA
            foreach($this->getLocal("dataService") as $key=>$value){

                $dataService[$key]              = $value;
            }
        }

        foreach($this->allLocal() as $key=>$value){

            if($key != "dataService"){

                $dataService["params"][$key]          = $value;
            }
        }

        $this->setJsDataVar("loadingElement", "");
        $this->setJsDataVar("dataService", $dataService);
    }
}