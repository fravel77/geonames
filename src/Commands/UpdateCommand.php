<?php namespace Arberd\Geonames\Commands;

use ZipArchive;
use ErrorException;
use RuntimeException;
use Arberd\Geonames\Importer;
use Arberd\Geonames\Commands\ImportCommand;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class UpdateCommand extends ImportCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'geonames:update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update the geonames database with modifications and deletions.';


	/**
	 * Create a new console command instance.
	 *
	 * @param  \Arberd\Geonames\Importer         $importer
	 * @param  \Illuminate\Filesystem\Filesystem  $filesystem
	 * @return void
	 */
	public function __construct(Importer $importer, Filesystem $filesystem, array $config)
	{
		parent::__construct($importer, $filesystem, $config);
	}

	/**
	 * Execute the console command.
	 *
     * @param string $extra
	 * @return void
	 */
	public function fire($extra = '')
	{
		// path to download our files
		$path = $this->getPath();

		// create the directory if it doesn't exists
		if ( ! $this->filesystem->isDirectory($path)) {
			$this->filesystem->makeDirectory($path, 0755, true);
		}

		$files = $this->getFiles();

		// loop all the files that we need to donwload
        $gc = [];
		foreach ($files as $file) {
            $file = sprintf($file, date('Y-m-d', strtotime("yesterday")));
			$filename = basename($file);

			if ($this->fileExists($path, $filename)) {
				$this->line("<info>File exists:</info> $filename");

				continue;
			}

			$this->line("<info>Downloading:</info> $file");
			$this->downloadFile($file, $path, $filename);
            $gc[] = $path . '/' . $filename;
		}


		$this->line("<info>Update database. This may take 'a while'...</info>");
		$this->seedCommand('UpdateNamesTableSeeder');


        $this->line("<info>Remove old entries from database. This may take 'a while'...</info>");
        $this->seedCommand('DeleteNamesTableSeeder');

        $this->line("<info>Garbage collection</info>");
        foreach ($gc as $file) {
            $this->filesystem->delete($file);
        }
	}

	/**
	 * Get the files to download.
	 *
	 * @return array
	 */
	public function getFiles()
	{
		return $this->config['update_files'];
	}

}

