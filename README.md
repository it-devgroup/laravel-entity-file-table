## 
## Install for laravel

**1.** Open file **config/app.php** and search (optional, using laravel discovered package system by default)

```
    'providers' => [
        ...
    ]
```

Add to section

```
        \ItDevgroup\LaravelEntityFileTable\Providers\EntityFileTableServiceProvider::class,
```

Example

```
    'providers' => [
        ...
        \ItDevgroup\LaravelEntityFileTable\Providers\EntityFileTableServiceProvider::class,
    ]
```

**2.** Run commands

For creating config file

```
php artisan vendor:publish --provider="ItDevgroup\LaravelEntityFileTable\Providers\EntityFileTableServiceProvider" --tag=config
```

For creating migration file

```
php artisan entity:file:table:publish --tag=migration
```

For generate table

```
php artisan migrate
```

## ENV variables

File .env

Auto rename all upload files

```
ENTITY_FILE_TABLE_FILENAME_GENERATE=1
```

Root folder for all files

```
ENTITY_FILE_TABLE_DEFAULT_FOLDER=uploads
```

## Custom model

###### Step 1

Create custom model for file

Example:

File: **app/CustomFile.php**

Content:

```
<?php

namespace App;

class CustomFile extends \ItDevgroup\LaravelEntityFileTable\Model\FileModel
{
}
```

If need change table name or need added other code:

```
<?php

namespace App;

class CustomFile extends \ItDevgroup\LaravelEntityFileTable\Model\FileModel
{
    protected $table = 'YOUR_TABLE_NAME';
    
    // other code
}
```

###### Step 2

Open **config/entity_file_table.php** and change parameter "model", example:

```
...
// remove
'model' => \ItDevgroup\LaravelEntityFileTable\Model\FileModel::class,
// add
'model' => \App\CustomFile::class,
```

## Custom lexicon

Create custom lexicon file

Example:

File: **lang/en/entity_file_table.php**

Content:

```
<?php

return [
    'delete' => 'File deletion error :path on disk :fileDriver',
    'delete_message' => 'File delete/replace error',
];
```

After added custom file, use "CustomFile" model everywhere, instead of "\ItDevgroup\LaravelEntityFileTable\Model\FileModel"

## Helpers

#### ItDevgroup\LaravelEntityFileTable\Helpers\FileHelper

```
// $file - \ItDevgroup\LaravelEntityFileTable\Model\FileModel
// generate url from file
return \ItDevgroup\LaravelEntityFileTable\Helpers\FileHelper::urlFile($file);

// generate url from data
return \ItDevgroup\LaravelEntityFileTable\Helpers\FileHelper::url('file.jpg', 'public');
```

## Create relations for custom model

One to one

```
/**
 * @return Illuminate\Database\Eloquent\Relations\MorphOne
 */
public function image()
{
    return $this->morphOne(\ItDevgroup\LaravelEntityFileTable\Model\FileModel::class, 'model', 'model_type', 'model_id');
}
```

One to many

```
/**
 * @return Illuminate\Database\Eloquent\Relations\MorphMany
 */
public function images()
{
    return $this->morphMany(\ItDevgroup\LaravelEntityFileTable\Model\FileModel::class, 'model', 'model_type', 'model_id');
}
```

**Example:**

```
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class User extends Model
{
    ...
    
    /**
     * @return MorphOne
     */
    public function image()
    {
        return $this->morphOne(\ItDevgroup\LaravelEntityFileTable\Model\FileModel::class, 'model', 'model_type', 'model_id');
    }
    
    /**
     * @return MorphMany
     */
    public function images()
    {
        return $this->morphMany(\ItDevgroup\LaravelEntityFileTable\Model\FileModel::class, 'model', 'model_type', 'model_id');
    }
}

```

## Usage

#### Initialize service

```
$service = app(\ItDevgroup\LaravelEntityFileTable\EntityFileTableServiceInterface::class);
```

or injected

```
// use
use ItDevgroup\LaravelEntityFileTable\EntityFileTableServiceInterface;
// constructor
public function __construct(
    EntityFileTableServiceInterface $EntityFileTableService
)
```

further we will use the variable **$service**

#### Methods for EntityFileTableServiceInterface

Set auto rename or not auto rename files

```
$service->setFilenameGenerate(true);
$service->setFilenameGenerate(false);
```

Get default disk (driver) in file system

```
$driver = $service->getDriver();
```

Get data of upload file, **return \ItDevgroup\LaravelEntityFileTable\FileData**

```
// without additional parameters (use default folder for file)
// $uploadFile - Illuminate\Http\UploadedFile
$fileData = $service->upload($uploadFile);

// with custom folder for file
$fileData = $service->upload($uploadFile, 'test');
$fileData = $service->upload($uploadFile, 'test/folder1');

// with custom file name
$fileData = $service->upload($uploadFile, 'test', 'filename.jpg');
$fileData = $service->upload($uploadFile, null, 'filename.jpg');
```

Get file model from upload file, **return \ItDevgroup\LaravelEntityFileTable\Model\FileModel**

```
// $fileData - \ItDevgroup\LaravelEntityFileTable\FileData
$file = $service->getFile($fileData);
```

Get file model from link, **return \ItDevgroup\LaravelEntityFileTable\Model\FileModel**

```
// $path - url for file
$file = $service->getFileExternal($path);
```

Save upload file

```
// save file on filesystem and database
// $file - return \ItDevgroup\LaravelEntityFileTable\Model\FileModel
$service->save($file);
$service->save($file, true);

// save file only on filesystem
$service->save($file, false);

// save file only in database
$service->saveDB($file);
```

Get file by ID, **return \ItDevgroup\LaravelEntityFileTable\Model\FileModel**

```
$file = $service->getFileById(1);
```

Get files by ID, **return \ItDevgroup\LaravelEntityFileTable\Model\FileModel[] | Illuminate\Database\Eloquent\Collection**

```
$files = $service->getFileByIds(collect([1,2,3]));
```

Delete file

```
// delete from filesystem and database
// $file - \ItDevgroup\LaravelEntityFileTable\Model\FileModel
$service->delete($file);
$service->delete($file, true);

// delete only from filesystem
$service->delete($file, false);

// delete only in database
$service->deleteDB($file);

// $file1, $file2, ... - \ItDevgroup\LaravelEntityFileTable\Model\FileModel
// $files - Collection
$files = collect([$file1, $file2, ...]);
$service->deleteAll($files);
// or
// $user - model user
$service->deleteAll($user->photos);
```

## Examples

Basic save

```
$uploadFile = \Illuminate\Http\UploadedFile::fake()->create('test.jpg', 10240, 'image/jpeg');
$fileData = $service->upload($uploadFile, null, 'file.jpg');
$file = $service->getFile($fileData);
$file->model()->associate(\App\User::find(1));
$service->save($file);
```

Save by relation oneToOne (save)

```
$service->setFilenameGenerate(true);
$uploadFile = \Illuminate\Http\UploadedFile::fake()->create('test.jpg', 10240, 'image/jpeg');
$fileData = $service->upload($uploadFile, null, 'file.jpg');
$file = $service->getFile($fileData);
$file->model()->associate(\App\User::find(1));
$service->save($file);
$user = \App\User::find(1);
$service->save($file, false);
$user->avatar()->save($file);
```

Save by relation oneToMany (clear all old files)

```
$uploadFile = \Illuminate\Http\UploadedFile::fake()->create('test.jpg', 10240, 'image/jpeg');
$fileData = $service->upload($uploadFile, null, 'file.jpg');
$file1 = $service->getFile($fileData);
$uploadFile = \Illuminate\Http\UploadedFile::fake()->create('test.jpg', 10240, 'image/jpeg');
$fileData = $service->upload($uploadFile, null, 'file.jpg');
$file2 = $service->getFile($fileData);
$user = \App\User::find(1);
$service->save($file1, false);
$service->save($file2, false);
$service->deleteAll($user->photos);
$user->photos()->saveMany([$file1,$file2]);
$user->refresh(); // if need at once show result
```

Get file by ID and delete

```
$file = $service->getFileById(1);
$service->delete($file);
```

return URL for file (before return on front)

```
$file = \ItDevgroup\LaravelEntityFileTable\Model\FileModel::find(1);
return $service->generateUrl($file->path, $file->file_driver);
// or
return \ItDevgroup\LaravelEntityFileTable\Helpers\FileHelper::urlFile($file);
```

