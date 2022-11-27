<?php


namespace Weigot\File;


class FileOperation
{
    public static function get($fileType)
    {
        $operation = null;
        switch ($fileType) {
            case FileType::IMAGE:
                $operation = new FileOperation();
                break;
        }
        return $operation;
    }
}