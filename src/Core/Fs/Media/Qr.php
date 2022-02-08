<?php

namespace Nubesys\Core\Fs\Media;

use Nubesys\Core\Services\FsService;
use Nubesys\File\Qr\Qr AS QrProcesor;

class Qr extends FsService {

    //MAIN ACTION
    public function main(){

        $qrParams                       = array();
        
        if($this->hasUrlParam(5)){

            $qrNameInfo                     = pathinfo($this->getUrlParam(5));
            $qrData                         = base64_decode(urldecode($qrNameInfo['filename']));
            $qrName                         = $qrNameInfo['filename'];
            $qrExtension                    = $qrNameInfo['extension'];
            
            $qr                             = new QrProcesor($this->getDI());

            if($qrExtension == "svg"){

                $qrFileData                 = $qr->getQrSvgData($qrData, $qrExtension, $qrModifiers);
            }else{

                $qrFileData                 = $qr->getQrImageData($qrData, $qrExtension, $qrModifiers);
                
            }
            
            $this->setServiceResponseFile($qrName . '.' . $qrExtension, $qr->getFileTypeMIME($qrExtension), $qrFileData);
        
        }else{

            //BAD REQUEST
        }
    }
}