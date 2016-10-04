<?php namespace Arberd\Geonames\Seeders;

class UpdateNamesTableSeeder extends DatabaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$path = $this->command->option('path');

		$this->importer->names('geonames_names', $path . '/modifications-'.date('Y-m-d', strtotime("yesterday")).'.txt', true);
	}

}