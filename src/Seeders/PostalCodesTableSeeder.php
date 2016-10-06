<?php namespace Arberd\Geonames\Seeders;

class PostalCodesTableSeeder extends DatabaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$path = $this->command->option('path');

		$this->importer->postalcodes('geonames_postal_codes', $path . '/postalcodes.txt', true);
	}

}