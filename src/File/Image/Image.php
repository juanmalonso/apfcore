<?php

namespace Nubesys\File\Image;

use Nubesys\File\File;
use Imagine\Imagick\Imagine;

class Image extends File {

    private $imagine;
    private $image;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->imagine      = null;
        $this->image        = null;

    }

    public function createImageFromBase64Data($p_data){

        $result = false;

        $this->imagine          = new Imagine();

        try {

            $this->image        = $this->imagine->load(\base64_decode($p_data));

            $result             = $this->image;

        }catch(\Exception $e){

            //TODO : ERRORES DE IMAGINE
        }

        return $result;
    }

    public function saveImageToFileSystem($p_name, $p_relativePath, $p_extension){

        $options = array(
            'png_compression_level' => 9,
            'jpeg_quality' => 100,
        );

        $basePath = $this->getFileAbsolutePath($p_relativePath);

        $savePath = $basePath . '/' . $p_name . '.' . $p_extension;
        
        $this->image->save($savePath, $options);
    }

    public function getImageData($p_name, $p_relativePath, $p_originalExtension, $p_newExtension, $imageModifiers){

        $result                                     = null;

        $this->imagine                              = new Imagine();

        $imageName                                  = $p_name;
        $imagePath                                  = $this->getFileAbsolutePath($p_relativePath) . '/' . $p_name . '.' . $p_originalExtension;
        
        if(file_exists($imagePath)){

            $this->image                            = $this->imagine->open($imagePath);
                
            foreach($imageModifiers as $modifierKey){

                foreach($this->getDI()->get('config')->files->images->modifiers as $mod){

                    $paramsArray = array();

                    if(preg_match($mod['expression'], $modifierKey, $paramsArray)){
                        
                        $imageName                  .= "_" . $modifierKey;
                        $imagePath                  = $this->getFileAbsolutePath($p_relativePath) . '/' . $imageName . '.' . $p_originalExtension;
                        
                        if(!file_exists($imagePath)){

                            $options = array(
                                'png_compression_level' => 9,
                                'jpeg_quality' => 100,
                            );
                            
                            if(isset($paramsArray[1]) && $paramsArray[1] == "qty"){

                                $options = $this->getCompressOptionsByQuality($paramsArray[2]);
                            }else{
                                
                                $modifierClass                  = 'Nubesys\\File\\Image\\Modifiers\\' . \Phalcon\Text::camelize($mod['class']);
                                
                                if(class_exists($modifierClass)){

                                    $modifierInstance           = new $modifierClass();
                                    
                                    $modifierMethod             = $mod['method'];

                                    if(method_exists($modifierInstance, $modifierMethod)){

                                        $this->image = $modifierInstance->$modifierMethod($this->image, $paramsArray);
                                    }
                                }else{

                                    //TODO: not exist modifier class
                                }
                            }

                            $this->image->save($imagePath, $options);

                        }else{

                            $this->image            = $this->imagine->open($imagePath);
                        }
                    }else{

                        //NOT MATCH
                    }
                }
            }
        }

        if(!is_null($this->image)){

            $result = $this->image->get($p_newExtension);
        }

        return $result;
    }

    protected function getCompressOptionsByQuality($p_quality){

        $pngCompress = 9;

        if($p_quality > 0 && $p_quality < 100){

            if($p_quality < 10){
                
                $pngCompress = 0;
            }else{
                 
                $qualityStr = (string)$p_quality;
                
                $pngCompress = (int)substr($qualityStr, 0, 1);
            }
            
        }else if($p_quality == 0){
             
            $pngCompress = 0;
        }else if($p_quality == 100){
             
            $pngCompress = 9;
        }else{
            
            $pngCompress = 9;
        }

        $options = array(
            'png_compression_level' => $pngCompress,
            'jpeg_quality' => $p_quality,
        );

        return $options;
    }
}