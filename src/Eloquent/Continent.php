<?php namespace Arberd\Geonames\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Continent extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'geonames_continents';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'code';

	/* -(  Relationships  )-------------------------------------------------- */

	public function countries()
	{
		return $this->hasMany('Arberd\Geonames\Eloquent\Country', 'continent');
	}

}