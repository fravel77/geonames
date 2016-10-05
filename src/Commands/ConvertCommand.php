<?php

namespace Arberd\Geonames\Commands;

use Arberd\Geonames\JsonRepository;
use Illuminate\Filesystem\Filesystem;
use Arberd\Geonames\Importer;

class ConvertCommand extends ImportCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'immogic:geonamesconvert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Geonames tables from json';

    /**
     * Repository implementation.
     *
     * @var \Arberd\Geonames\RepositoryInterface
     */
    protected $repository;

    /**
     * Create a new console command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $filesystem
     * @return void
     */
    public function __construct(Filesystem $filesystem, array $config)
    {
        $basePath = $this->option('basepath');
        $basePath = $basePath ? $basePath : $config['path'] . '/';

        $repository = new JsonRepository($filesystem, $basePath);

        $app = $this->laravel;

        $app['geonames.repository'] = $app->share(function($app) use ($repository)
        {
            return $repository;
        });

        $app->bind('Arberd\Geonames\RepositoryInterface', function($app)
        {
            return $app['geonames.repository'];
        });

        parent::__construct(new Importer($app['geonames.repository']), $filesystem, $config);
    }
}
