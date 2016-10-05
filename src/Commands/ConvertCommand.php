<?php

namespace Arberd\Geonames\Commands;

use Arberd\Geonames\JsonRepository;
use Illuminate\Filesystem\Filesystem;
use Arberd\Geonames\Importer;
use Symfony\Component\Console\Input\InputOption;

class ConvertCommand extends ImportCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'geonames:convert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert Geonames files to json';

    /**
     * Repository implementation.
     *
     * @var \Arberd\Geonames\RepositoryInterface
     */
    protected $repository;

    /**
     * @var string
     */
    protected $basePath = '';

    /**
     * Create a new console command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $filesystem
     * @return void
     */
    public function __construct(Filesystem $filesystem, array $config)
    {
        $basePath = $config['json_path'] . '/';

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

        $this->repository = $repository;

        parent::__construct(new Importer($app['geonames.repository']), $filesystem, $config);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $wipeJson   = $this->input->getOption('wipe-json');

        $wipeJson and $this->filesystem->deleteDirectory($this->basePath);

        // create the directory if it doesn't exists
        if ( ! $this->filesystem->isDirectory($this->basePath)) {
            $this->filesystem->makeDirectory($this->basePath, 0755, true);
        }

        parent::fire();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('country', null, InputOption::VALUE_REQUIRED, 'Downloads just the specific country.'),
            array('development', null, InputOption::VALUE_NONE, 'Downloads an smaller version of names (~10MB).'),
            array('fetch-only', null, InputOption::VALUE_NONE, 'Just download the files.'),
            array('wipe-files', null, InputOption::VALUE_NONE, 'Wipe old downloaded files and fetch new ones.'),
            array('wipe-json', null, InputOption::VALUE_NONE, 'Wipe converted json files.'),
        );
    }
}
