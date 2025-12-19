<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();

            // Основные поля
            $table->string('title');              // название альбома
            $table->text('description')->nullable(); // описание
            $table->date('release_date')->nullable(); // дата выхода
            $table->string('image_path')->nullable(); // путь к картинке

            $table->timestamps();    // created_at, updated_at
            $table->softDeletes();   // deleted_at для Soft Deletes (расширенный уровень)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albums');
    }
}
