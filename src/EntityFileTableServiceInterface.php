<?php

namespace ItDevgroup\LaravelEntityFileTable;

use ItDevgroup\LaravelEntityFileTable\Model\FileModel;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

/**
 * Interface EntityFileTableServiceInterface
 * @package ItDevgroup\LaravelEntityFileTable
 */
interface EntityFileTableServiceInterface
{
    /**
     * External driver name
     * @type string
     */
    public const DRIVER_EXTERNAL = 'external';


    /**
     * @return bool
     */
    public function isFilenameGenerate(): bool;

    /**
     * @param bool $filenameGenerate
     */
    public function setFilenameGenerate(bool $filenameGenerate): void;

    /**
     * @param UploadedFile $uploadFile
     * @param string|null $path
     * @param string|null $title
     * @return FileData
     */
    public function upload(UploadedFile $uploadFile, ?string $path = null, ?string $title = null): ?FileData;

    /**
     * @param string|null $path
     * @param string|null $driver
     * @return string
     */
    public function generateUrl(?string $path, ?string $driver): ?string;

    /**
     * @return string
     */
    public function getDriver();

    /**
     * @param FileModel $file
     * @param bool $db
     */
    public function delete(FileModel $file, bool $db = true): void;

    /**
     * @param FileModel $file
     * @return bool
     */
    public function deleteDB(FileModel $file): bool;

    /**
     * @param Collection $files
     */
    public function deleteAll(Collection $files): void;

    /**
     * @param FileModel $file
     * @param bool $db
     * @return bool
     */
    public function save(FileModel $file, bool $db = true): bool;

    /**
     * @param FileModel $file
     * @return bool
     */
    public function saveDB(FileModel $file): bool;

    /**
     * @param FileData|null $fileData
     * @return FileModel
     */
    public function getFile(?FileData $fileData): ?FileModel;

    /**
     * @param string|null $path
     * @return FileModel
     */
    public function getFileExternal(?string $path): ?FileModel;

    /**
     * @param int|null $id
     * @return FileModel
     */
    public function getFileById(?int $id): ?FileModel;

    /**
     * @param Collection $ids
     * @return EloquentCollection
     */
    public function getFileByIds(Collection $ids): EloquentCollection;
}
