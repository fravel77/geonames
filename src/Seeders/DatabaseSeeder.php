<?php namespace Arberd\Geonames\Seeders;

use Arberd\Geonames\Importer;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

	/**
	 * The importer instance.
	 *
	 * @var \Arberd\Geonames\Importer
	 */
	protected $importer;

	/**
	 * Create a new Seeder instance.
	 *
	 * @param  \Arberd\Geonames\Importer  $importer
	 * @return void
	 */
	public function __construct(Importer $importer)
	{
		$this->importer = $importer;
	}

	public function run()
	{

	}

}