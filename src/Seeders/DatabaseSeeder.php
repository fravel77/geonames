<?php namespace Arberd\Geonames\Seeders;

use Arberd\Geonames\DatabaseRepository;
use Arberd\Geonames\Importer;
use Arberd\Geonames\JsonRepository;
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

        $this->checkRepository();
	}

	public function run()
	{

	}

	public function checkRepository()
    {
        $repo = $this->command->option('repository');
        switch ($repo) {
            case 'json':
                $app = app();
                $config = config('geonames.import', array());
                $basePath = $config['json_path'] . '/';
                $repository = new JsonRepository($app['files'], $basePath);
                $this->importer->setRepository($repository);
                break;
            case 'db':
                $app = app();
                $connection = $app['db']->connection();
                $repository = new DatabaseRepository($connection);
                $this->importer->setRepository($repository);
                break;
        }
    }

}