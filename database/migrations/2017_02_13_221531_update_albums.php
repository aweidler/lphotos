<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAlbums extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('albums', function (Blueprint $table) {
			$table->unsignedInteger('parent')->nullable()->after('name');
			$table->string('location', 500);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('albums', function(Blueprint $table){
			$table->dropColumn('location');
			$table->dropColumn('parent');
		});
	}
}
