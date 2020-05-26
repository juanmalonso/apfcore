<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04/08/17
 * Time: 02:44 PM
 */

namespace Nubesys\File\Image\Modifiers;

use Imagine\Image\Point;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class Resize
{
    public function w($p_image, $p_params){

        $size  = $p_image->getSize();

        $ow = $size->getWidth();
        $oh = $size->getHeight();

        $nw = $p_params[2];
        $np = ($nw*100)/$ow;
        $nh = round(($oh*$np)/100);

        $p_image->resize(new Box($nw, $nh));

        return $p_image;
    }

    public function h($p_image, $p_params){

        $size  = $p_image->getSize();

        $ow = $size->getWidth();
        $oh = $size->getHeight();

        $nh = $p_params[2];
        $np = ($nh*100)/$oh;
        $nw = round(($ow*$np)/100);

        $p_image->resize(new Box($nw, $nh));

        return $p_image;
    }

    public function wh($p_image, $p_params){

        $p_image->resize(new Box($p_params[2], $p_params[3]));

        return $p_image;
    }
}