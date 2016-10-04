<?php namespace Arberd\Geonames;

use Illuminate\Database\Connection;

class DatabaseRepository implements RepositoryInterface {

	/**
	 * The database connection instance.
	 *
	 * @var \Illuminate\Database\Connection
	 */
	protected $connection;

	/**
	 * Create a new database repository instance.
	 *
	 * @param  \Illuminate\Database\Connection  $connection
	 * @return void
	 */
	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * Truncate the table.
	 *
	 * @param  string  $table
	 * @return void
	 */
	public function truncate($table)
	{
		$this->getTable($table)->truncate();
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
		return $this->getTable($table)->count();
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
		$this->getTable($table)->insert($data);
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
        return $this->getTable($table)->update($data);
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
        return $this->getTable($table)->delete($id);
    }

	/**
	 * Begin a new database query with the given table.
	 *
	 * @return \Illuminate\Database\Query\Builder
	 */
	protected function getTable($table)
	{
		return $this->connection->table($table);
	}

	/**
	 * Get the database connection instance.
	 *
	 * @return \Illuminate\Database\Connection
	 */
	public function getConnection()
	{
		return $this->connection;
	}


}