<?php

namespace ItDevgroup\LaravelEntityFileTable\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
     * @var string
     */
    protected $table = 'files';
    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    /**
     * @var array
     */
    protected $casts = [
        'size' => 'int',
    ];
    /**
     * @var mixed
     */
    public $resource = null;

    /**
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo(null, 'model_type', 'model_id');
    }
}
