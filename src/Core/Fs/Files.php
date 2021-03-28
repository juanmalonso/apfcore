<?php

namespace Nubesys\Core\Fs;

use Nubesys\Core\Services\FsService;
use Nubesys\File\File;

class Files extends FsService {

    //MAIN ACTION
    public function main(){

        $fileParams                     = array();

        $urlParamNum                    = 0;
        foreach($this->allUrlParams() as $urlParam){

            if($urlParamNum > 4){

                $fileParams[]           = $urlParam;
            }

            $urlParamNum++;
        }

        $fileNameInfo                   = pathinfo(array_pop($fileParams));
        $fileName                       = $fileNameInfo['filename'];
        $fileExtension                  = $fileNameInfo['extension'];

        $file                           = new File($this->getDI());

        $fileData                       = $file->getFileData($fileName, implode("/",$fileParams), $fileExtension);

        $this->setServiceResponseFile($fileName . '.' . $fileExtension, $file->getFileTypeMIME($fileExtension), $fileData);
    }
}