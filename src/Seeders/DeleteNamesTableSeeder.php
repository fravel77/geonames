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

		$this->importer->delete('geonames_names', $path . '/deletes-'.date('Y-m-d', strtotime("yesterday")).'.txt');
	}

}