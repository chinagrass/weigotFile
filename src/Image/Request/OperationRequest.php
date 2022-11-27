<?php


namespace Weigot\File\Image\Request;


class OperationRequest
{
    /**
     * @var int
     */
    public $model;
    /**
     * @var int
     */
    public $width;
    /**
     * @var int
     */
    public $height;
    /**
     * @var float
     */
    public $size;
    /**
     * @var int
     */
    public $quality;
    /**
     * @var string
     */
    public $pixelColor;
    public $water = [];
}