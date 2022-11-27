<?php

namespace Weigot\File\Image;

use Weigot\File\Enum\Image\OperationRequestModel;
use Weigot\File\Exception\OperationException;
use Weigot\File\Image\Request\DrawRequest;
use Weigot\File\Image\Request\OperationRequest;
use Weigot\File\Image\Response\OperationResponse;

class Operation
{
    /**
     * @var OperationRequest
     */
    private $request;
    /**
     * @var \Imagick
     */
    private $image;

    /**
     * @var OperationResponse
     */
    private $response;

    /**
     * @return OperationResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function __construct()
    {
        $this->response = new OperationResponse();
    }

    /**
     * @return OperationRequest
     * @throws OperationException
     */
    public function getRequest()
    {
        if (!$this->request) {
            throw new OperationException("请设置正确的参数");
        }
        return $this->request;
    }

    /**
     * @param OperationRequest $request
     */
    public function setRequest(OperationRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Imagick
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param $image
     * @throws \ImagickException
     */
    public function setImage($image)
    {
        $this->image = new \Imagick();
        $this->image->readImageBlob($image);
    }


    public function draw(DrawRequest $request)
    {
        return $this;
    }

    /**
     * 压缩图片
     * @return $this
     * @throws OperationException
     * @throws \ImagickException
     */
    public function compress()
    {
        $request = $this->getRequest();
        $imagick = $this->getImage();
        $format = $imagick->getImageFormat();
        $w = $imagick->getImageWidth();
        $h = $imagick->getImageHeight();
        if (!$w || !$h) {
            Throw new OperationException("图片不存在");
        }
        $height = !empty($request->size) ? $h * $request->size : $request->height;
        $width = !empty($request->size) ? $w * $request->size : $request->width;
        switch ($request->model) {
            case OperationRequestModel::AUTO:
                $imagick->resizeImage($width, $height, \Imagick::FILTER_CATROM, 1, true);
                $width = $imagick->getImageWidth();
                $height = $imagick->getImageHeight();
                if (!$width || !$height) {
                    Throw new OperationException("图片压缩失败");
                }
                break;
            case OperationRequestModel::FILL_IN_COLOR:
                $background = new \ImagickPixel($request->pixelColor);
                $canvas = new \Imagick();
                $canvas->newImage($width, $height, $background, $format);
                if ($w / $h > $width / $height) //上下补
                {
                    //以宽度进行缩放
                    $resizeWidth = $width;
                    $resizeHeight = $h;
                    $x = 0;
                    $y = floor(($height - $imagick->getImageHeight()) / 2);
                } else {
                    //以高度进行缩放
                    $resizeWidth = $w;
                    $resizeHeight = $height;
                    $x = floor(($width - $imagick->getImageWidth()) / 2);
                    $y = 0;
                }
                $imagick->resizeImage($resizeWidth, $resizeHeight, \Imagick::FILTER_CATROM, 1, true);
                $canvas->compositeImage($imagick, \Imagick::COMPOSITE_OVER, $x, $y);
                $this->image = $canvas;
                break;
            default:
            case OperationRequestModel::SCALING:
                if ($w / $h > $width / $height) {
                    //以高度进行缩放
                    $resizeWidth = $w;
                    $resizeHeight = $height;
                    $x = ceil(($height * $w / $h - $width) / 2);
                    $y = 0;
                } else {
                    //以宽度进行缩放
                    $resizeWidth = $width;
                    $resizeHeight = $h;
                    $x = 0;
                    $y = ceil(($width * $h / $w - $height) / 2);
                }
                $imagick->resizeImage($resizeWidth, $resizeHeight, \Imagick::FILTER_CATROM, 1, true);
                $imagick->cropImage($width, $height, $x, $y);
                break;
        }
        $response = $this->getResponse();
        $response->height = $height;
        $response->width = $width;
        $response->format = $format;
        return $this;
    }

    /**
     * @return OperationResponse
     * @throws OperationException
     */
    public function run()
    {
        $request = $this->getRequest();
        $imagick = $this->getImage();
        //设置压缩质量
        if ($request->quality) {
            $imagick->setImageCompressionQuality($request->quality);
        }

        //去除exif信息
        $imagick->stripImage();
        $buff = $imagick->getImageBlob();
        $response = $this->getResponse();
        $response->buff = $buff;
        return $response;
    }

    public function water()
    {
        return $this;
    }
}