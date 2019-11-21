<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupReadableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_readable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('group_id')->unsigned();
            $table->integer('readable_id')->unsigned();
            $table->string('readable_type');
            $table->boolean('allow')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_readable');
    }
}
