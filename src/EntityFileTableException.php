<?php

namespace ItDevgroup\LaravelEntityFileTable;

use Exception;
use ItDevgroup\LaravelEntityFileTable\Model\FileModel;
use Illuminate\Support\Facades\Lang;

/**
 * Class EntityFileTableException
 * @package ItDevgroup\LaravelEntityFileTable
 */
class EntityFileTableException extends Exception
{
    /**
     * @var string|null
     */
    private ?string $text = null;

    /**
     * @param FileModel $file
     * @return self
     */
    public static function delete(FileModel $file): self
    {
        $key1 = Lang::has('entity_file_table.delete')
            ? 'entity_file_table.delete' : 'entityFileTable::default.delete';
        $key2 = Lang::has('entity_file_table.delete_message')
            ? 'entity_file_table.delete_message' : 'entityFileTable::default.delete_message';
        return (new self(
            Lang::get(
                $key1,
                [
                    'path' => $file->path,
                    'fileDriver' => $file->file_driver
                ]
            )
        ))->setText(Lang::get($key2));
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     * @return self
     */
    public function setText(?string $text): self
    {
        $this->text = $text;
        return $this;
    }
}
