<?php

namespace Nubesys\Core\Fs\Media;

use Nubesys\Core\Services\FsService;
use Nubesys\File\Image\Image AS ImageProcesor;
use Nubesys\Data\DataSource\DataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Objects as ObjectsDataSource;

class Image extends FsService {

    //MAIN ACTION
    public function main(){

        $imageParams                    = array();
        $imageParams[]                  = 'org';
        
        $urlParamNum                    = 0;
        foreach($this->allUrlParams() as $urlParam){

            //TODO : que el numero de inicio sea parametrizable
            if($urlParamNum > 4){

                if($urlParam != 'org'){

                    $imageParams[]      = $urlParam;
                }
            }

            $urlParamNum++;
        }

        $imageNameInfo                  = pathinfo(array_pop($imageParams));
        $imageName                      = $imageNameInfo['filename'];
        $imageExtension                 = $imageNameInfo['extension'];
        $imageModifiers                 = $imageParams;

        $imageDataSourceOptions         = array();
        $imageDataSourceOptions["model"]= $this->getDI()->get('config')->files->images->model;

        $imageDataSource                = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $imageDataSourceOptions));

        $imageObjectData                = $imageDataSource->getData($imageName);

        if(isset($imageObjectData['path']) && isset($imageObjectData['extension'])){

            $image                      = new ImageProcesor($this->getDI());
            
            $imageData                  = $image->getImageData($imageName, $imageObjectData['path'], $imageObjectData['extension'], $imageExtension, $imageModifiers);

            $this->setServiceResponseFile($imageName . '.' . $imageExtension, $image->getFileTypeMIME($imageExtension), $imageData);

        }else{

            //TODO : NO IMAGE DATA
        }
        
        
    }
}