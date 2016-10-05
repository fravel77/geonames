<?php namespace Arberd\Geonames;

use Illuminate\Filesystem\Filesystem;

class JsonRepository implements RepositoryInterface {

	/**
	 * The database connection instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $filesystem;

    /**
     * @var string
     */
    protected $basePath = '';

	/**
	 * Create a new json repository instance.
	 *
	 * @param  \Illuminate\Filesystem\Filesystem  $filesystem
     *
	 * @return void
	 */
	public function __construct(Filesystem $filesystem, $basePath = '')
	{
		$this->filesystem = $filesystem;
        $this->basePath = $basePath;
	}

	/**
	 * Truncate the table.
	 *
	 * @param  string  $table
	 * @return void
	 */
	public function truncate($table)
	{
        $this->filesystem->put(
            $this->basePath . $this->getJsonFileName($table),
            ''
        );
	}

	/**
	 * Checks if a table is empty.
	 *
	 * @param  string   $table
	 * @return boolean
	 */
	public function isEmpty($table)
	{
		return ($this->count($table) === 0);
	}

	/**
	 * Counts the elements in a table.
	 *
	 * @param  string  $table
	 * @return integer
	 */
	public function count($table)
	{
        $f = fopen($this->basePath . $this->getJsonFileName($table), 'rb');
        $lines = 0;

        while (!feof($f)) {
            $lines += substr_count(fread($f, 8192), "\n");
        }

        fclose($f);

        return $lines;
	}

	/**
	 * Insert an array to a given table.
	 *
	 * @param  string  $table
	 * @param  array   $data
	 * @return void
	 */
	public function insert($table, array $data)
	{
        $this->filesystem->append(
            $this->basePath . $this->getJsonFileName($table),
            json_encode($data) . "\n"
        );
	}

    /**
     * update an array on a given table.
     *
     * @param  string  $table
     * @param  array   $data
     *
     * @return int
     */
    public function update($table, array $data)
    {
        return false;
    }

    /**
     * remove a record from given table.
     *
     * @param  string  $table
     * @param  int     $id
     *
     * @return boolean
     */
    public function delete($table, $id)
    {
        return false;
    }

	protected function getJsonFileName($name)
    {
        return $name.'.json';
    }


}