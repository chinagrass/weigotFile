<?php


namespace Weigot\File;


use Weigot\File\Image\Operation;

class FileOperation
{
    public static function get($fileType)
    {
        $operation = null;
        switch ($fileType) {
            case FileType::IMAGE:
                $operation = new Operation();
                break;
        }
        return $operation;
    }
}