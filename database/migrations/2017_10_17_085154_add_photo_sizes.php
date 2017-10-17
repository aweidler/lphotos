<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhotoSizes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_entries', function(Blueprint $table){
        	$table->integer('width')->default(0);
        	$table->integer('height')->default(0);
        	$table->text('exif')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_entries', function(Blueprint $table){
        	$table->dropColumn('width');
        	$table->dropColumn('height');
        	$table->dropColumn('exif');
        });
    }
}
