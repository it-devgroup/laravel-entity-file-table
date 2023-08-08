<?php

namespace ItDevgroup\LaravelEntityFileTable\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Config;

/**
 * Class FileModel
 * @package ItDevgroup\LaravelEntityFileTable\Model
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string $title
 * @property string $filename
 * @property int $size
 * @property string $extension
 * @property string $mime
 * @property string $path
 * @property string $hash_sum
 * @property string $file_driver
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class FileModel extends Model
{
    /**
     * @inheritdoc
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    /**
     * @inheritdoc
     */
    protected $casts = [
        'size' => 'int',
    ];
    /**
     * @var mixed
     */
    public $resource = null;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('entity_file_table.table');

        parent::__construct($attributes);
    }

    /**
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo(null, 'model_type', 'model_id');
    }
}
