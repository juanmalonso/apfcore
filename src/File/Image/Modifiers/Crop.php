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

class Crop
{
    public function cp($p_image, $p_params){

        $point = new Point($p_params[2], $p_params[3]);

        $p_image->crop($point, new Box($p_params[4], $p_params[5]));

        return $p_image;
    }

    public function cparp($p_image, $p_params){

        $size  = $p_image->getSize();

        $ow = $size->getWidth();
        $oh = $size->getHeight();

        $nw = round(($ow*$p_params[2])/100);
        $nh = round(($oh*$p_params[3])/100);

        $point = new Point(($ow/2)-($nw/2), ($oh/2)-($nh/2));

        $p_image->crop($point, new Box($ow, $nh));

        /*if($p_params[2] > $p_params[3]){

            $nh = round(($ow*$p_params[3])/100);
            var_dump($nh);
            $point = new Point(0, ($oh/2)-($nh/2));
            var_dump($point);
            $p_image->crop($point, new Box($ow, $nh));

        }else{

            $nw = round(($oh*$p_params[2])/100);
            var_dump($nw);
            $point = new Point(($ow/2)-($nw/2), 0);
            var_dump($point);
            $p_image->crop($point, new Box($nw, $oh));
        }*/

        return $p_image;
    }

    public function sq($p_image, $p_params){

        $size  = $p_image->getSize();

        $ow = $size->getWidth();
        $oh = $size->getHeight();

        if($ow > $oh){

            $point = new Point(($ow/2)-($oh/2), 0);
            $p_image->crop($point, new Box($oh, $oh));
        }else{

            $point = new Point(0, ($oh/2)-($ow/2));
            $p_image->crop($point, new Box($ow, $ow));
        }

        return $p_image->resize(new Box($p_params[2],$p_params[2]));
    }
}