<?php
namespace Nubesys\Core\Ui\Components\Modules;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class ModuleComponent extends VueUiComponent {

    public function mainAction(){

        $this->addJsSource("https://cdn.jsdelivr.net/burry.js/0.1.0/burry.js");
        
        if($this->hasLocal("referenceName")){

            $this->registerReference($this->getLocal("referenceName"));
        }

        $dataService                            = array();
        $dataService['name']                    = "";
        $dataService['type']                    = "service";
        $dataService['scope']                   = "service";
        $dataService['emitter']                 = "";
        $dataService['params']                  = array();
        
        $this->setDataSercvice($dataService);

        $this->setJsDataVar("dataScopeRegister", []);
        
    }

    protected function setDataSercvice($p_data){
        
        $dataService                            = $p_data;

        if($this->hasLocal("dataService")){
            
            //OVERWRITE INITIAL DATA
            foreach($this->getLocal("dataService") as $key=>$value){

                $dataService[$key]              = $value;
            }
        }

        if($this->hasLocal("moduleAction")){

            $dataService["params"]["moduleAction"]          = $this->getLocal("moduleAction");
        }

        if($this->hasLocal("actionLayout")){

            $dataService["params"]["actionLayout"]          = $this->getLocal("actionLayout");
        }

        if($this->hasLocal("componentIndex")){

            $dataService["params"]["componentIndex"]        = (string)$this->getLocal("componentIndex");
        }

        $this->setJsDataVar("loadingElement", "");
        $this->setJsDataVar("dataService", $dataService);
    }
}