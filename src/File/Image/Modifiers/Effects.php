<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04/08/17
 * Time: 02:44 PM
 */

namespace Nubesys\File\Image\Modifiers;

use Imagine\Image\Palette\Color\ColorInterface;

class Effects
{
    public function blur($p_image, $p_params){

        $p_image->effects()->blur($p_params[2]);

        return $p_image;
    }

    public function gamma($p_image, $p_params){

        $p_image->effects()->gamma($p_params[2]);

        return $p_image;
    }

    public function color($p_image, $p_params){

        $color = $p_image->palette()->color('#' . $p_params[2]);

        $p_image->effects()->colorize($color);

        return $p_image;
    }

    public function gray($p_image, $p_params){

        $p_image->effects()->grayscale();

        return $p_image;
    }

    public function negative($p_image, $p_params){

        $p_image->effects()->negative();

        return $p_image;
    }

}