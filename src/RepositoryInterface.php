<?php namespace Arberd\Geonames;

interface RepositoryInterface {

	public function truncate($table);
	public function isEmpty($table);
	public function count($table);
	public function insert($table, array $data);
    public function update($table, array $data);
    public function delete($table, $id);


}