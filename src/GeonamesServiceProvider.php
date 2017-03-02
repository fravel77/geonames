<?php namespace Arberd\Geonames;

use Illuminate\Support\ServiceProvider;

class GeonamesServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
    protected $defer = false;
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(realpath(__DIR__ . '/config/config.php'), 'geonames');
        $this->publishes([
            realpath(__DIR__ . '/config/config.php') => $this->app->configPath() . '/geonames.php'
        ], 'config');
        $this->publishes([
            realpath(__DIR__ . '/migrations') => $this->app->databasePath() . '/migrations'
        ], 'migrations');
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepository();
        $this->registerCommands();
    }
    /**
     * Register the repository implementation.
     *
     * @return void
     */
    protected function registerRepository()
    {
        $app = $this->app;
        $app->bind('geonames.repository', function($app)
        {
            $connection = $app['db']->connection();
            return new DatabaseRepository($connection);
        });
        $app->bind('Arberd\Geonames\RepositoryInterface', function($app)
        {
            return $app['geonames.repository'];
        });
    }
    /**
     * Register the auth related console commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $app = $this->app;
        $app->bind('command.geonames.install', function($app)
        {
            return new Commands\InstallCommand;
        });
        $app->bind('command.geonames.import', function($app)
        {
            $config = config('geonames.import', array());
            return new Commands\ImportCommand(new Importer($app['geonames.repository']), $app['files'], $config);
        });
        $app->bind('command.geonames.seed', function($app)
        {
            return new Commands\SeedCommand($app['db']);
        });
        $app->bind('command.geonames.truncate', function($app)
        {
            return new Commands\TruncateCommand($app['geonames.repository']);
        });
        $this->commands([
            'command.geonames.install',
            'command.geonames.import',
            'command.geonames.seed',
            'command.geonames.truncate'
        ]);
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}
