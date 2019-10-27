<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
            $table->string('slug')->unique();
            $table->string('title')->unique();
            $table->datetime('date');
            $table->integer('section_id')->unsigned();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('restrict')->onUpdate('restrict');
            $table->string('author');
            $table->mediumText('article_desc');
            $table->enum('status', ['PUBLISHED','DRAFT'])->default('DRAFT');
            $table->integer('views')->unsigned();
            $table->rememberToken();
            $table->timestamps();
        });
        /**
         * Add full text index
         */
        DB::statement('ALTER TABLE gallery ADD FULLTEXT fulltext_index (title, article_desc)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gallery');
    }
}

