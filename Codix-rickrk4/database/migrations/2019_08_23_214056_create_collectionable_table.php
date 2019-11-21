<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collectionables', function (Blueprint $table) {
            $table->integer('collection_id')->unsigned();
            $table->integer('collectionable_id')->unsigned();
            $table->string('collectionable_type');
            //$table->primary(['collection_id','collectionable_id','collectionable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collectionables');
    }
}
