<?php

namespace Nubesys\File\Qr;

use Nubesys\File\File;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class Qr extends File {

    public function __construct($p_di)
    {
        parent::__construct($p_di);
    }

    public function getQrSvgData($p_qrData, $p_qrExtension, $p_qrModifiers){

        $optionsArray   = array();

        $optionsArray['eccLevel']               = QRCode::ECC_L;
        $optionsArray['outputType']             = $this->getQrOutputType($p_qrExtension);
        $optionsArray['imageBase64']            = false;
        $optionsArray['version']                = QRCode::VERSION_AUTO;

        return (new QRCode(new QROptions($optionsArray)))->render($p_qrData);
    }

    public function getQrImageData($p_qrData, $p_qrExtension, $p_qrModifiers){

        $optionsArray   = array();

        $optionsArray['eccLevel']               = QRCode::ECC_L;
        $optionsArray['outputType']             = $this->getQrOutputType($p_qrExtension);
        $optionsArray['imageBase64']            = false;
        $optionsArray['version']                = QRCode::VERSION_AUTO;

        /*$optionsArray['moduleValues']           = [
                                                    // finder
                                                    1536 => [0,0,0],
                                                    6    => [255,255,255],
                                                    // alignment
                                                    2560 => [0,0,0],
                                                    10   => [255,0,0],
                                                    // timing
                                                    3072 => [0,0,0],
                                                    12   => [255,255,255],
                                                    // format
                                                    3584 => [0,0,0],
                                                    14   => [255,255,255],
                                                    // version
                                                    4096 => [0,0,0],
                                                    16   => [255,255,255],
                                                    // data
                                                    1024 => [0,0,0],
                                                    4    => [255,255,255],
                                                    // darkmodule
                                                    512  => [0,0,0],
                                                    // separator
                                                    8    => [255,255,255],
                                                    // quietzone
                                                    18   => [255,255,255]
                                                ];
        */

        return (new QRCode(new QROptions($optionsArray)))->render($p_qrData);
    }

    protected function getQrOutputType($p_qrExtension){

        $result  = QRCode::OUTPUT_IMAGE_PNG;

        switch ($p_qrExtension) {
            case 'png':
                $result         = QRCode::OUTPUT_IMAGE_PNG;
                break;
            
            case 'jpg':
                $result         = QRCode::OUTPUT_IMAGE_JPG;
                break;
    
            case 'gif':
                $result         = QRCode::OUTPUT_IMAGE_GIF;
                break;
            
            case 'svg':
                $result         = QRCode::OUTPUT_MARKUP_SVG;
                break;
            
            default:
                $result         = QRCode::OUTPUT_IMAGE_PNG;
                break;
        }

        return $result;
    }
}