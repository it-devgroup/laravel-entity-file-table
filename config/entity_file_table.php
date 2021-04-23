<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table name
    |--------------------------------------------------------------------------
    */
    'table' => 'files',

    /*
    |--------------------------------------------------------------------------
    | Model
    |--------------------------------------------------------------------------
    | Default: \ItDevgroup\LaravelEntityFileTable\Model\FileModel::class
    | Change to your custom class if you need to extend the model or change the table name
    | A custom class must inherit the base class \ItDevgroup\LaravelEntityFileTable\Model\FileModel
    */
    'model' => \ItDevgroup\LaravelEntityFileTable\Model\FileModel::class,

    /*
    |--------------------------------------------------------------------------
    | generate filename
    |--------------------------------------------------------------------------
    | if true, original filename when uploading, will be replaced with a hash
    */
    'filename_generate' => env('ENTITY_FILE_TABLE_FILENAME_GENERATE', true),

    /*
    |--------------------------------------------------------------------------
    | Default folder for files
    |--------------------------------------------------------------------------
    */
    'folder' => env('ENTITY_FILE_TABLE_DEFAULT_FOLDER', 'uploads')
];
