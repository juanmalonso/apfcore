<?php
namespace Nubesys\Core\Ui\Components\Modules\Manager;

use Nubesys\Core\Ui\Components\Modules\ModuleComponent;

class Manager extends ModuleComponent {

    public function mainAction(){

        parent::mainAction();

        $this->placeVueCustomElement("table-manager-header");
        $this->placeVueCustomElement("to-state-action");

        $this->setManagerTabs();
        $this->setManagerSliders();
    }

    protected function setManagerTabs(){

        $tabs                               = array();
        $activeTabDefault                   = false;

        if($this->hasLocal("tabs")){
            
            foreach($this->getLocal("tabs") as $reference=>$component){
                
                if($activeTabDefault == ""){

                    $activeTabDefault       = $reference;
                }

                $componentParams                    = array();

                if(isset($component['referenceName'])){

                    $componentParams['referenceName']                   = $component['referenceName'];
                }

                if(isset($component['dataService'])){

                    $dataService = $component['dataService'];

                    /*
                    if($this->hasLocal("moduleAction")){

                        $dataService["params"]["moduleAction"]          = $this->getLocal("moduleAction");
                    }

                    if($this->hasLocal("actionLayout")){

                        $dataService["params"]["actionLayout"]          = $this->getLocal("actionLayout");
                    }

                    if($this->hasLocal("componentIndex")){

                        $dataService["params"]["componentIndex"]        = (string)$this->getLocal("componentIndex");
                    }

                    $dataService["params"]["tabIndex"]                  = $reference;

                    */

                    $componentParams['dataService']                     = $dataService;
                    
                }

                $this->placeVueCustomElement($component['elementTag'], $component['classPath'], $componentParams);
                
            }
        }

        $this->setJsDataVar("activeTabDefault", $activeTabDefault);
    }

    protected function setManagerSliders(){
        
        $sliders                                = array();

        if($this->hasLocal("sliders")){
            
            foreach($this->getLocal("sliders") as $reference=>$component){

                $componentParams                    = array();

                if(isset($component['referenceName'])){

                    $componentParams['referenceName']                   = $component['referenceName'];
                }

                if(isset($component['dataService'])){

                    $dataService = $component['dataService'];

                    $componentParams['dataService']                     = $dataService;
                }

                $this->placeVueCustomElement($component['elementTag'], $component['classPath'], $componentParams);

                $sliders[$component['elementTag']] = \Phalcon\Text::camelize($component['elementTag']);
            }
        }

        $this->setViewVar("sliders", $sliders);

        $this->setJsDataVar("maxzindex", 1000);
    }
}