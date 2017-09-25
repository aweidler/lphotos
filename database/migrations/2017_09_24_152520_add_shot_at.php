<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShotAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
   		Schema::table('file_entries', function(Blueprint $table){
   			$table->timestamp('shot_at');
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
        	$table->dropColumn('shot_at');
        });
    }
}
