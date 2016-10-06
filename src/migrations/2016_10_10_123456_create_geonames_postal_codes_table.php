<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostalCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        //taken from : http://download.geonames.org/export/zip/

		Schema::create('geonames_postal_codes', function(Blueprint $table)
		{
            $table->string('iso_alpha2', 2)->index();       // country code: iso country code, 2 characters
            $table->string('postal_code', 20)->index();     // postal code: varchar(20)
            $table->string('place_name', 180);              // place name: varchar(180)
            $table->string('admin_name1', 100)->index();    // admin name1: 1. order subdivision (state) varchar(100)
            $table->string('admin_code1', 20);              // admin code1: 1. order subdivision (state) varchar(20)
            $table->string('admin_name2', 100)->index();    // admin name2: 2. order subdivision (county/province) varchar(100)
            $table->string('admin_code2', 20);              // admin code2: 2. order subdivision (county/province) varchar(20)
            $table->string('admin_name3', 100)->index();    // admin name3: 3. order subdivision (community) varchar(100)
            $table->string('admin_code3', 20);              // admin code3: 3. order subdivision (community) varchar(20)
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
			$table->integer('accuracy');                    // accuracy: accuracy of lat/lng from 1=estimated to 6=centroid

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('geonames_postal_codes');
	}

}
