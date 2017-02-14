<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFileTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_entries', function (Blueprint $table) {
        	$table->string('hash', 32)->after('id');
        	$table->unique('hash');

        	$table->unsignedInteger('album');
        	$table->string('tags', 500);
        	$table->unsignedBigInteger('size');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_entries', function (Blueprint $table) {
            $table->dropColumn('hash');
            $table->dropColumn('tags');
            $table->dropColumn('album');
            $table->dropColumn('size');
        });
    }
}
