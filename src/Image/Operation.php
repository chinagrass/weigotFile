<?php

namespace Weigot\File\Image;

use Weigot\File\Image\Request\CompressRequest;
use Weigot\File\Image\Request\DrawRequest;
use Weigot\File\Image\Request\WaterRequest;

class Operation
{

    public function draw(DrawRequest $request)
    {

    }

    public function compress(CompressRequest $request)
    {
        $file = "../test/coke.png";
        $im = new \Imagick($file);

var_dump($im);die;
        $width = $im->getImageWidth();
        $height = $im->getImageHeight();
        if($width > $height)
            $im->resizeImage($size, 0, imagick::FILTER_LANCZOS, 1);
        else
            $im->resizeImage(0 , $size, imagick::FILTER_LANCZOS, 1);

        $im->setImageCompression(true);
        $im->setCompression(Imagick::COMPRESSION_JPEG);
        $im->setCompressionQuality(20);

        $im->writeImage(ALBUM_PATH . $this->path . $this->filename . $extension);
        $im->clear();
        $im->destroy();
    }

    public function water(WaterRequest $request)
    {

    }
}