<?php
namespace   Nubesys\Core\Ui\Components\App\Editors\Form\FormCustomElements;

use Nubesys\Vue\Ui\Components\VueUiComponent;

use Nubesys\Data\DataSource\DataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Objects as ObjectsDataSource;
use Nubesys\File\Image\Image;

class ImageSelector extends VueUiComponent {

    public function mainAction(){

        //ARRAY OF IMAGES
        $this->setJsDataVar("images", new \stdClass());
        
        //GALLERY
        $this->setJsDataVar("galleryData", array());
        $this->setJsDataVar("galleryPage", 1);
        $this->setJsDataVar("galleryRows", 10);

        //UPLOAD IMAGE DATA
        $this->setJsDataVar("imageSelectedIndex", "_0");

        $this->setJsDataVar("imageSelectedName", "");

        $this->setJsDataVar("uploadImageName", "");
        $this->setJsDataVar("uploadImageDescription", "");
        $this->setJsDataVar("uploadImageExtension", "");
        $this->setJsDataVar("uploadImageTypemime", "");
        $this->setJsDataVar("uploadImageSize", "");
        $this->setJsDataVar("uploadImagePath", $this->getDI()->get('config')->files->images->model);
        $this->setJsDataVar("uploadImageData", "");
    }

    public function uploadService(){

        if($this->hasJsonParam()){

            $param                              = $this->getJsonParam();

            if(isset($param["name"]) && isset($param["mimetype"]) && isset($param["size"]) && isset($param["path"]) && isset($param["image"])){

                $image                              = new Image($this->getDI());

                $imageInstance                      = $image->createImageFromBase64Data($param['image']);

                $imageDataSourceOptions             = array();
                $imageDataSourceOptions["model"]    = $this->getDI()->get('config')->files->images->model;

                $imageDataSource                    = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $imageDataSourceOptions));

                $imageSaveData                      = array();
                $imageSaveData['name']              = $param["name"];
                $imageSaveData['mimetype']          = $param["mimetype"];
                $imageSaveData['size']              = $param['size'];
                $imageSaveData['path']              = $param['path'];
                $imageSaveData['description']       = (isset($param['description'])) ? $param['description'] : "";

                $imageSaveData['extension']         = $image->getFileExtension($imageSaveData['mimetype']);
                if(isset($param['extension'])){

                    if($image->getFileTypeMIME($param['extension'])){

                        $imageSaveData['extension'] = $param['extension'];
                    }
                }

                $imageSaveData['width']             = $imageInstance->getSize()->getWidth();
                $imageSaveData['height']            = $imageInstance->getSize()->getHeight();

                $imageSaveResult                    = $imageDataSource->addData($imageSaveData);

                $image->saveImageToFileSystem($imageSaveResult, $imageSaveData['path'], $imageSaveData['extension']);

                //TODO : VALIDAR SI SE GUARDO CORRECTAMENTE

                $result                             = array();
                $result['id']                       = $imageSaveResult;

                $imageSaveData['name']              = $result['id'];
                
                $result['imageSaveData']            = $imageSaveData;

                $this->setServiceSuccess($result);
            }else{

                $this->setServiceError("Parametros Incorrectos");    
            }
            

        }else{

            $this->setServiceError("Parametros Incorrectos");
        }
    }

    public function galleryService(){

        if($this->hasJsonParam()){

            $param                              = $this->getJsonParam();

            if(isset($param["page"]) && isset($param["rows"])){

                $imageDataSourceOptions             = array();
                $imageDataSourceOptions["model"]    = $this->getDI()->get('config')->files->images->model;

                $imageDataSource                    = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $imageDataSourceOptions));

                $dataSourceQuery                    = array();
                $dataSourceQuery['page']            = (isset($param["page"])) ? $param["page"] : 1;
                $dataSourceQuery['rows']            = (isset($param["rows"])) ? $param["rows"] : 10;

                $this->setServiceSuccess($imageDataSource->getData($dataSourceQuery));
            }else{

                $this->setServiceError("Datasource param not Found");    
            }
            

        }else{

            $this->setServiceError("Invalid Params");
        }
    }
}