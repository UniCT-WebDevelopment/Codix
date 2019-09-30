<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorComicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('author_comic', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('comic_id')->unsigned();
            $table->integer('author_id')->unsigned();
            $table->string('role')->nullable();
            $table->unique(['comic_id','author_id','role']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('author_comic');
    }
}
