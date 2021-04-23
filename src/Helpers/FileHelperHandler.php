<?php

namespace ItDevgroup\LaravelEntityFileTable\Helpers;

use ItDevgroup\LaravelEntityFileTable\EntityFileTableServiceInterface;
use ItDevgroup\LaravelEntityFileTable\Model\FileModel;

/**
 * Class FileHelperHandler
 * @package ItDevgroup\LaravelEntityFileTable\Helpers
 */
final class FileHelperHandler
{
    /**
     * @var EntityFileTableServiceInterface
     */
    private EntityFileTableServiceInterface $entityFileTableService;

    /**
     * FileHelperHandler constructor.
     * @param EntityFileTableServiceInterface $entityFileTableService
     */
    public function __construct(
        EntityFileTableServiceInterface $entityFileTableService
    ) {
        $this->entityFileTableService = $entityFileTableService;
    }

    /**
     * @param string $path
     * @param string $driver
     * @return string|null
     */
    public function url(string $path, string $driver): ?string
    {
        return $this->entityFileTableService->generateUrl($path, $driver);
    }

    /**
     * @param FileModel $file
     * @return string|null
     */
    public function urlFile(FileModel $file): ?string
    {
        return $this->entityFileTableService->generateUrl($file->path, $file->file_driver);
    }
}
