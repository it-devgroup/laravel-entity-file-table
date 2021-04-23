<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFiles
 */
class CreateFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            '{{TABLE_NAME}}',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('model_type')->nullable()->index();
                $table->integer('model_id')->nullable()->index();
                $table->string('title')->nullable();
                $table->string('filename')->nullable();
                $table->bigInteger('size')->nullable();
                $table->string('extension')->nullable();
                $table->string('mime')->nullable();
                $table->string('path')->nullable();
                $table->string('hash_sum')->nullable();
                $table->string('file_driver')->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('{{TABLE_NAME}}');
    }
}
