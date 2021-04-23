<?php

namespace ItDevgroup\LaravelEntityFileTable;

use Exception;
use ItDevgroup\LaravelEntityFileTable\Model\FileModel;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

/**
 * Class EntityFileTableService
 * @package ItDevgroup\LaravelEntityFileTable
 */
class EntityFileTableService implements EntityFileTableServiceInterface
{
    /**
     * @var Request
     */
    private Request $request;
    /**
     * @var string
     */
    private string $model;
    /**
     * @var bool
     */
    private bool $filenameGenerate;
    /**
     * @var string|null
     */
    private ?string $defaultFolder;

    /**
     * @param Request $request
     */
    public function __construct(
        Request $request
    ) {
        $this->request = $request;
        $this->model = Config::get('entity_file_table.model');
        $this->filenameGenerate = (bool)Config::get('entity_file_table.filename_generate');
        $this->defaultFolder = Config::get('entity_file_table.folder');
    }

    /**
     * @return bool
     */
    public function isFilenameGenerate(): bool
    {
        return $this->filenameGenerate;
    }

    /**
     * @param bool $filenameGenerate
     */
    public function setFilenameGenerate(bool $filenameGenerate): void
    {
        $this->filenameGenerate = $filenameGenerate;
    }

    /**
     * @param UploadedFile|null $uploadFile
     * @param string|null $path
     * @param string|null $title
     * @return FileData
     */
    public function upload(?UploadedFile $uploadFile, ?string $path = null, ?string $title = null): ?FileData
    {
        if (!$uploadFile) {
            return null;
        }

        $file = FileData::fromUploadFile($uploadFile, $title);

        if ($this->filenameGenerate) {
            $file->setFilename(
                sprintf(
                    '%s.%s',
                    md5(time() . rand(1, 10000)),
                    $file->getExtension()
                )
            );
        }

        $path = $this->getPath($path);
        if ($path) {
            $path = sprintf(
                '%s/%s',
                $path,
                $file->getFilename()
            );
        } else {
            $path = $file->getFilename();
        }
        $file->setPath($path);
        $file->setDriver($this->getDriver());

        return $file;
    }

    /**
     * @param string|null $path
     * @param string|null $driver
     * @return string|null
     */
    public function generateUrl(?string $path, ?string $driver): ?string
    {
        if ($driver == self::DRIVER_EXTERNAL) {
            return $path;
        }

        /** @var FilesystemAdapter $storage */
        try {
            $storage = Storage::disk($driver);
        } catch (Exception $e) {
            $storage = Storage::disk(Config::get('filesystems.default'));
        }

        return $storage->url($path);
    }

    /**
     * @return string
     */
    public function getDriver(): string
    {
        return Config::get('filesystems.default');
    }

    /**
     * @param FileModel $file
     * @param bool $db
     * @return bool
     */
    public function save(FileModel $file, bool $db = true): bool
    {
        $return = Storage::disk($file->file_driver)->put($file->path, $file->resource);

        if ($db) {
            $this->saveDB($file);
        }

        return $return;
    }

    /**
     * @param FileModel $file
     * @return bool
     */
    public function saveDB(FileModel $file): bool
    {
        return $file->save();
    }

    /**
     * @param FileModel $file
     * @param bool $db
     * @throws EntityFileTableException
     */
    public function delete(FileModel $file, bool $db = true): void
    {
        if ($db) {
            $this->deleteDB($file);
        }

        if ($file->file_driver == self::DRIVER_EXTERNAL) {
            return;
        }

        if (!Storage::disk($file->file_driver)->exists($file->path)) {
            return;
        }

        if (!Storage::disk($file->file_driver)->delete($file->path)) {
            throw EntityFileTableException::delete($file);
        }
    }

    /**
     * @param FileModel $file
     * @return bool
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function deleteDB(FileModel $file): bool
    {
        return $file->delete();
    }

    /**
     * @param Collection $files
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function deleteAll(Collection $files): void
    {
        foreach ($files as $file) {
            $this->delete($file);
        }
    }

    /**
     * @param FileData|null $fileData
     * @return FileModel
     */
    public function getFile(?FileData $fileData): ?FileModel
    {
        if (!$fileData) {
            return null;
        }

        $fileObj = new $this->model();
        $fileObj->title = $fileData->getTitle();
        $fileObj->filename = $fileData->getFilename();
        $fileObj->size = $fileData->getSize();
        $fileObj->extension = $fileData->getExtension();
        $fileObj->mime = $fileData->getMimeType();
        $fileObj->path = $fileData->getPath();
        $fileObj->hash_sum = $fileData->getHashSum();
        $fileObj->file_driver = $fileData->getDriver();
        $fileObj->resource = $fileData->getResource();

        return $fileObj;
    }

    /**
     * @param string|null $path
     * @return FileModel
     */
    public function getFileExternal(?string $path): ?FileModel
    {
        if (!$path) {
            return null;
        }

        $fileObj = new $this->model();
        $fileObj->title = basename($path);
        $fileObj->filename = basename($path);
        $fileObj->size = 0;
        $explode = explode('.', basename($path));
        $fileObj->extension = end($explode);
        $fileObj->mime = null;
        $fileObj->path = $path;
        $fileObj->hash_sum = null;
        $fileObj->file_driver = self::DRIVER_EXTERNAL;

        return $fileObj;
    }

    /**
     * @param int|null $id
     * @return FileModel
     */
    public function getFileById(?int $id): ?FileModel
    {
        if (!$id) {
            return null;
        }

        return $this->model::find($id);
    }

    /**
     * @param Collection $ids
     * @return EloquentCollection
     */
    public function getFileByIds(Collection $ids): EloquentCollection
    {
        if (!$ids->count()) {
            $ids->push(-1);
        }

        return $this->model::find($ids);
    }

    /**
     * @param string|null $path
     * @return string
     */
    private function getPath(?string $path): string
    {
        if ($path) {
            return $path;
        }

        return $this->defaultFolder;
    }
}
