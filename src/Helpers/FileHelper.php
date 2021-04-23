<?php

namespace ItDevgroup\LaravelEntityFileTable\Helpers;

use ItDevgroup\LaravelEntityFileTable\Model\FileModel;
use Illuminate\Support\Facades\Facade;

/**
 * Class FileHelper
 * @package ItDevgroup\LaravelEntityFileTable\Helpers
 * @method static mixed urlFile(FileModel $file)
 * @method static mixed url(string $path, string $driver)
 */
final class FileHelper extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return FileHelperHandler::class;
    }
}
