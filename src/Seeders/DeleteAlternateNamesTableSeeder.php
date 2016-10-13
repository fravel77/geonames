<?php namespace Arberd\Geonames\Seeders;

class DeleteAlternateNamesTableSeeder extends DatabaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$path = $this->command->option('path');

		$this->importer->delete('geonames_alternate_names', $path . '/alternateNamesDeletes-'.date('Y-m-d', strtotime("yesterday")).'.txt');
	}

}