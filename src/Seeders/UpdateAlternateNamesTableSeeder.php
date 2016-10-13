<?php namespace Arberd\Geonames\Seeders;

class UpdateAlternateNamesTableSeeder extends DatabaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$path = $this->command->option('path');

		$this->importer->names('geonames_alternate_names', $path . '/alternateNamesModifications-'.date('Y-m-d', strtotime("yesterday")).'.txt', true);
	}

}