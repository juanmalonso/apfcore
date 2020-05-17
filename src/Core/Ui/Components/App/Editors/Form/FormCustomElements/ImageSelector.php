<?php
namespace   Nubesys\Core\Ui\Components\App\Editors\Form\FormCustomElements;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class ImageSelector extends VueUiComponent {

    public function mainAction(){
        
        //UPLOAD IMAGE DATA

        $this->setJsDataVar("uploadImageName", "");
        $this->setJsDataVar("uploadImageDescription", "");
        $this->setJsDataVar("uploadImageExtension", "");
        $this->setJsDataVar("uploadImageTypemime", "");
        $this->setJsDataVar("uploadImageSize", "");
        $this->setJsDataVar("uploadImageData", "");
    }

    public function uploadService(){

        if($this->hasJsonParam()){

            $param      = $this->getJsonParam();

            /*if(isset($param["datasource"])){

                $objectsSelectorDataSource              = $this->getDataSource($param["datasource"]);

                $dataSourceQuery            = array();
                $dataSourceQuery['page']    = (isset($param["page"])) ? $param["page"] : 1;
                $dataSourceQuery['rows']    = (isset($param["rows"])) ? $param["rows"] : 10;

                $this->setServiceSuccess($objectsSelectorDataSource->getData($dataSourceQuery));
            }else{

                $this->setServiceError("Datasource param not Found");    
            }*/
            

        }else{

            $this->setServiceError("Invalid Params");
        }
    }
}