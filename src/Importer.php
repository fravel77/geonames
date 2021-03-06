<?php namespace Arberd\Geonames;

use Clousure;
use RuntimeException;

class Importer {

	/**
	 * Repository implementation.
	 *
	 * @var \Arberd\Geonames\RepositoryInterface
	 */
	protected $repository;

	/**
	 * Create a new instance of Importer.
	 *
	 * @param  \Arberd\Geonames\RepositoryInterface  $repository
	 * @return void
	 */
	public function __construct(RepositoryInterface $repository)
	{
		$this->repository = $repository;
	}

    /**
     * set the repository
     *
     * @param RepositoryInterface $repository
     */
	public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

	/**
	 * Parse the names file and inserts it to the database.
	 *
	 * @param  string  $table
	 * @param  string  $path
     * @param  boolean $update
     *
     * @return void
	 */
	public function names($table, $path, $update = false)
	{
		!$update ? $this->isEmpty($table) : null;

		$repository = $this->repository;

		$base_path_mod = str_replace('\\', '/', $path);

		$query = sprintf("LOAD DATA LOCAL INFILE '%s'
		INTO TABLE $table
		CHARACTER SET 'utf8mb4'
		(`id`, `name`, `ascii_name`, `alternate_names`, `latitude`, `longitude`, `f_class`,
		`f_code`, `country_id`, `cc2`, `admin1`, `admin2`, `admin3`, `admin4`, `population`, `elevation`,
		`gtopo30`, `timezone_id`, `modification_at`);", $base_path_mod);

		$repository->getConnection()->getpdo()->exec($query);

		// $this->parseFile($path, function($row) use ($table, $repository, $update)
		// {
		// 	$insert = array(
		// 		'id'              => $row[0],
		// 		'name'            => $row[1],
		// 		'ascii_name'      => $row[2],
		// 		'alternate_names' => $row[3],
		// 		'latitude'        => $row[4],
		// 		'longitude'       => $row[5],
		// 		'f_class'         => $row[6],
		// 		'f_code'          => $row[7],
		// 		'country_id'      => $row[8],
		// 		'cc2'             => $row[9],
		// 		'admin1'          => $row[10],
		// 		'admin2'          => $row[11],
		// 		'admin3'          => $row[12],
		// 		'admin4'          => $row[13],
		// 		'population'      => $row[14],
		// 		'elevation'       => $row[15]? $row[15]:null,
		// 		'gtopo30'         => $row[16],
		// 		'timezone_id'     => $row[17],
		// 		'modification_at' => date('U', strtotime($row[18])),
		// 	);
		//
    //         if ($update) {
    //             $repository->update($table, $insert);
    //         } else {
		// 	    $repository->insert($table, $insert);
    //         }
		// });
	}

    /**
     * Parse file and remove entries from the database.
     *
     * @param  string  $table
     * @param  string  $path
     * @return void
     */
    public function delete($table, $path)
    {
        $repository = $this->repository;

        $this->parseFile($path, function($row) use ($table, $repository)
        {
            $repository->delete($table, $row[0]);
        });
    }

	/**
	 * Parse the countries file and inserts it to the database.
	 *
	 * @param  string  $table
	 * @param  string  $path
	 * @return void
	 */
	public function countries($table, $path)
	{
		$this->isEmpty($table);

		$repository = $this->repository;

		$base_path_mod = str_replace('\\', '/', $path);

		$query = sprintf("LOAD DATA LOCAL INFILE '%s'
		INTO TABLE $table
		CHARACTER SET 'UTF8'
		IGNORE 51 LINES
		(`iso_alpha2`, `iso_alpha3`, `iso_numeric`, `fips_code`, `name`, `capital`, `area`, `population`, `continent_id`,
		`tld`, `currency`, `currency_name`, `phone`, `postal_code_format`, `postal_code_regex`, `languages`, `name_id`, `neighbours`,
		`equivalent_fips_code`);", $base_path_mod);

		 $repository->getConnection()->getpdo()->exec($query);

	}

	/**
	 * Inserts the continents to the database.
	 *
	 * @param  string  $table
	 * @param  array   $continents
	 * @return void
	 */
	public function continents($table, $continents)
	{
		$this->isEmpty($table);


		// $base_path_mod = str_replace('\\', '/', $path);
		//
		// $query = sprintf("LOAD DATA LOCAL INFILE 'continentCodes.txt'
		// INTO TABLE continentCodes
		// CHARACTER SET 'UTF8'
		// FIELDS TERMINATED BY ','
		// (`code`, `name`, `name_id`);", $base_path_mod);
		//
		// $repository->getConnection()->getpdo()->exec($query);

		foreach ($continents as $row) {
			$insert = array(
				'code'    => $row[0],
				'name'    => $row[1],
				'name_id' => $row[2],
			);

			$this->repository->insert($table, $insert);
		}
	}

	/**
	 * Parse the language codes file and inserts it to the database.
	 *
	 * @param  string  $table
	 * @param  string  $path
	 * @return void
	 */
	public function languageCodes($table, $path)
	{
		$this->isEmpty($table);

		$repository = $this->repository;

		$base_path_mod = str_replace('\\', '/', $path);

		$query = sprintf("LOAD DATA LOCAL INFILE '%s'
		INTO TABLE $table
		CHARACTER SET 'UTF8'
		IGNORE 1 LINES
		(`iso_639_3`, `iso_639_2`, `iso_639_1`, `language_name`);", $base_path_mod);

		$repository->getConnection()->getpdo()->exec($query);

	}

	/**
	 * Parse the admin divisions and subdivisions file and inserts it to the
	 * database.
	 *
	 * @param  string  $table
	 * @param  string  $path
	 * @return void
	 */
	public function adminDivions($table, $path)
	{
		$this->isEmpty($table);

		$repository = $this->repository;

		$base_path_mod = str_replace('\\', '/', $path);

		$query = sprintf("LOAD DATA LOCAL INFILE '%s'
		INTO TABLE $table
		CHARACTER SET 'UTF8'
		(`code`, `name`, `name_ascii`, `name_id`);", $base_path_mod);

		$repository->getConnection()->getpdo()->exec($query);

	}

	/**
	 * Parse the hierachies file and inserts it to the database.
	 *
	 * @param  string  $table
	 * @param  string  $path
	 * @return void
	 */
	public function hierarchies($table, $path)
	{
		$this->isEmpty($table);

		$repository = $this->repository;

		$base_path_mod = str_replace('\\', '/', $path);

		$query = sprintf("LOAD DATA LOCAL INFILE '%s'
		INTO TABLE $table
		CHARACTER SET 'UTF8'
		(`parent_id`, `child_id`, `type`);", $base_path_mod);

		$repository->getConnection()->getpdo()->exec($query);

	}

	/**
	 * Parse the features file and inserts it to the database.
	 *
	 * @param  string  $table
	 * @param  string  $path
	 * @return void
	 */
	public function features($table, $path)
	{
		$this->isEmpty($table);

		$repository = $this->repository;

		$base_path_mod = str_replace('\\', '/', $path);

		$query = sprintf("LOAD DATA LOCAL INFILE '%s'
		INTO TABLE $table
		CHARACTER SET 'UTF8'
		(`code`, `name`, `description`);", $base_path_mod);

		$repository->getConnection()->getpdo()->exec($query);

	}

	/**
	 * Parse the timezones file and inserts it to the database.
	 *
	 * @param  string  $table
	 * @param  string  $path
	 * @return void
	 */
	public function timezones($table, $path)
	{
		$this->isEmpty($table);

		$repository = $this->repository;

		$base_path_mod = str_replace('\\', '/', $path);

		$query = sprintf("LOAD DATA LOCAL INFILE '%s'
		INTO TABLE $table
		CHARACTER SET 'UTF8'
		IGNORE 1 LINES
		(`country_code`, `id`, `gmt_offset`, `dst_offset`, `raw_offset`);", $base_path_mod);

		$repository->getConnection()->getpdo()->exec($query);

	}

	/**
	 * Parse the alternate names file and inserts it to the database.
	 *
	 * @param  string  $table
	 * @param  string  $path
	 * @return void
	 */
	public function alternateNames($table, $path)
	{
		$this->isEmpty($table);

		$repository = $this->repository;

		$base_path_mod = str_replace('\\', '/', $path);

		$query = sprintf("LOAD DATA LOCAL INFILE '%s'
		INTO TABLE $table
		CHARACTER SET 'utf8mb4'
		(`id`, `name_id`, `iso_language`, `alternate_name`, `is_preferred`, `is_short`, `is_colloquial`, `is_historic`);", $base_path_mod);

		$repository->getConnection()->getpdo()->exec($query);

	}

    /**
     * Parse the postal codes file and inserts it to the database.
     *
     * @param  string  $table
     * @param  string  $path
     * @return void
     */
    public function postalcodes($table, $path)
    {
        $this->isEmpty($table);

        $repository = $this->repository;

				$base_path_mod = str_replace('\\', '/', $path);

				$query = sprintf("LOAD DATA LOCAL INFILE '%s'
				INTO TABLE $table
				CHARACTER SET 'UTF8'
				IGNORE 1 LINES
				(`iso_alpha2`, `postal_code`, `place_name`, `admin_name1`, `admin_code1`, `admin_name2`, `admin_code2`, `admin_name3`,
				`admin_code3`, `latitude`, `longitude`, `accuracy`);", $base_path_mod);

				$repository->getConnection()->getpdo()->exec($query);

    }

	/**
	 * Prevent wrong executons of the importer.
	 *
	 * @param  string   $table
	 * @return void
	 */
	protected function isEmpty($table)
	{
		if ( ! $this->repository->isEmpty($table)) {
			throw new RuntimeException("The table [$table] is not empty.");
		}
	}

	/**
	 * Parse a given file and return the CSV lines as an array.
	 *
	 * @param  string     $path
	 * @param  \Closure  $callback
	 * @return void
	 */
	protected function parseFile($path, $callback)
	{
		$handle = fopen($path, 'r');

		if ( ! $handle) {
			throw new RuntimeException("Impossible to open file: $path");
		}

		// gets the lines and run the callback until we reach the end of file
		while ( ! feof($handle)) {
			$line = fgets($handle, 1024 * 32);

			// ignore empty lines and comments
			if ( ! $line or $line === '' or strpos($line, '#') === 0) continue;

			// our CSV is <TAB> separated so we only need to conver it to an array
			$line = explode("\t", $line);

			// finally run our clousure with the line
			$callback($line);
		}

		fclose($handle);
	}

}
