<?php

namespace Nubesys\Core\Ui\Components\Basic;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class Button extends VueUiComponent {

    public function mainAction($p_params){

        $this->setJsDataVar("buttonClass", "primary");
        $this->setJsDataVar("iconClass", "dog");

        $this->readJsonFilesTest();
    }

    public function testService($p_params){
        
        $buttonsClasses = array("orange", "red", "black", "grey", "yellow", "olive", "green", "blue", "violet", "purple", "pink", "brown");

        sleep(3);

        $result = new \stdClass();
        $result->buttonClass = $buttonsClasses[rand(0, 11)];

        $this->setServiceSuccess($result);
    }

    public function dataService($p_params){
        
        $iconClasses = array("cat", "crow", "dog", "dove", "dragon", "feather", "feather alternate", "fish", "frog", "hippo", "horse", "kiwi");

        sleep(3);

        $result = new \stdClass();
        $result->iconClass = $iconClasses[rand(0, 11)];

        $this->setServiceSuccess($result);
    }

    protected function readJsonFilesTest($p_sufixPath = "data/objects/models/"){

        foreach($this->getDI()->get('config')->loader->apfclassespaths as $apfclasspath){

            $finalPath = "";

            if($this->getDI()->get('config')->main->enviroment == 'dev'){

                $finalPath .= $apfclasspath->devpath . "json/" . $p_sufixPath;
            }else{

                $finalPath .= $apfclasspath->propath . "json/" . $p_sufixPath;
            }

            if(is_dir($finalPath)){

                if($dh = opendir($finalPath)){

                    while (($file = readdir($dh)) !== false){

                        if($file != "." && $file != ".."){

                            var_dump($file);
                        }
                    }
                }
            }
            /*foreach($namespaces as $namespace=>$path){
    
                if(strpos($replacepath['propath'], $path) != -1){
    
                    $namespaces[$namespace] = str_replace($replacepath['propath'], $replacepath['devpath'],$namespaces[$namespace]);
                }
            }*/
        }

        exit();
    }
}