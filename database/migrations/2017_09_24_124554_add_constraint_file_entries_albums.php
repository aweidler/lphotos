<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConstraintFileEntriesAlbums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_entries', function(Blueprint $table){
        	$table->foreign('album_id')
        		  ->references('id')->on('albums')
        		  ->onDelete('cascade');
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
    		$table->dropForeign('file_entries_album_id_foreign');
    	});
    }
}
