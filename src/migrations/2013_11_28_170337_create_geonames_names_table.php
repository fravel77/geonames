<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeonamesNamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('geonames_names', function(Blueprint $table)
		{
			$table->increments('id')->comment('integer id of record in geonames database');
			$table->string('name', 200)->collation('utf8mb4_general_ci')->comment('name of geographical point (utf8) varchar(200)"');
			$table->string('ascii_name', 200)->comment('name of geographical point in plain ascii characters, varchar(200)');
			$table->string('alternate_names', 4000)->collation('utf8mb4_general_ci')->comment('alternatenames, comma separated, ascii names automatically transliterated, convenience attribute from alternatename table, varchar(10000)');
			$table->decimal('latitude', 10, 7)->comment('latitude in decimal degrees (wgs84)');
			$table->decimal('longitude', 10, 7)->comment('longitude in decimal degrees (wgs84)');
			$table->string('f_class', 1)->comment('see http://www.geonames.org/export/codes.html, char(1)');
			$table->string('f_code', 10)->comment('see http://www.geonames.org/export/codes.html, varchar(10)');
			$table->string('country_id', 2)->index()->comment('ISO-3166 2-letter country code, 2 characters');
			$table->string('cc2', 200)->comment('alternate country codes, comma separated, ISO-3166 2-letter country code, 200 characters');
			$table->string('admin1', 20)->index()->comment('fipscode (subject to change to iso code), see exceptions below, see file admin1Codes.txt for display names of this code; varchar(20)');
			$table->string('admin2', 80)->index()->comment('code for the second administrative division, a county in the US, see file admin2Codes.txt; varchar(80)');
			$table->string('admin3', 20)->index()->comment('code for third level administrative division, varchar(20)');
			$table->string('admin4', 20)->index()->comment('code for fourth level administrative division, varchar(20)');
			$table->integer('population')->index()->comment('bigint (8 byte int)');
			$table->smallInteger('elevation')->unsigned()->comment('in meters, integer');
			$table->smallInteger('gtopo30')->unsigned()->comment("digital elevation model, srtm3 or gtopo30, average elevation of 3''x3'' (ca 90mx90m) or 30''x30'' (ca 900mx900m) area in meters, integer. srtm processed by cgiar/ciat.");
			$table->string('timezone_id', 40)->index()->comment('the timezone id (see file timeZone.txt) varchar(40)');
			$table->date('modification_at')->comment('date of last modification in yyyy-MM-dd format');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('geonames_names');
	}

}
