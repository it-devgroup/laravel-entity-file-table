<?php

namespace ItDevgroup\LaravelEntityFileTable;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;

/**
 * Class FileData
 * @package ItDevgroup\LaravelEntityFileTable
 */
class FileData
{
    /**
     * @var string|null
     */
    private ?string $title;
    /**
     * @var string|null
     */
    private ?string $filename;
    /**
     * @var int|null
     */
    private ?int $size;
    /**
     * @var string|null
     */
    private ?string $extension;
    /**
     * @var string|null
     */
    private ?string $mimeType;
    /**
     * @var string|null
     */
    private ?string $path;
    /**
     * @var string|null
     */
    private ?string $hashSum;
    /**
     * @var string|null
     */
    private ?string $driver;
    /**
     * @var mixed
     */
    private $resource;

    /**
     * @param UploadedFile $file
     * @param string|null $title
     * @return self
     * @noinspection PhpDocMissingThrowsInspection
     */
    public static function fromUploadFile(UploadedFile $file, ?string $title = null): self
    {
        return (new self())
            ->setTitle($title ?? $file->getClientOriginalName())
            ->setFilename($file->getClientOriginalName())
            ->setSize($file->getSize())
            ->setExtension($file->extension())
            ->setMimeType($file->getMimeType())
            ->setHashSum($file->getRealPath())
            ->setResource($file->get());
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return self
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     * @return self
     */
    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * @param int|null $size
     * @return self
     */
    public function setSize(?int $size): self
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * @param string|null $extension
     * @return self
     */
    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param string|null $mimeType
     * @return self
     */
    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     * @return self
     */
    public function setPath(?string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHashSum(): ?string
    {
        return $this->hashSum;
    }

    /**
     * @param string|null $path
     * @return self
     */
    public function setHashSum(?string $path): self
    {
        $this->hashSum = hash_file('md5', $path);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDriver(): ?string
    {
        return $this->driver;
    }

    /**
     * @param string|null $driver
     * @return self
     */
    public function setDriver(?string $driver): self
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param mixed $resource
     * @return self
     */
    public function setResource($resource): self
    {
        $this->resource = $resource;
        return $this;
    }
}
