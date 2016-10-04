<?php

return array(

	'import' => array(

		'path' => storage_path() . '/meta/geonames',

        'files' => array(
            'countries' => 'http://download.geonames.org/export/dump/countryInfo.txt',
            'names'     => 'http://download.geonames.org/export/dump/allCountries.zip',
            'alternate' => 'http://download.geonames.org/export/dump/alternateNames.zip',
            'hierarchy' => 'http://download.geonames.org/export/dump/hierarchy.zip',
            'admin1'    => 'http://download.geonames.org/export/dump/admin1CodesASCII.txt',
            'admin2'    => 'http://download.geonames.org/export/dump/admin2Codes.txt',
            'feature'   => 'http://download.geonames.org/export/dump/featureCodes_en.txt',
            'timezones' => 'http://download.geonames.org/export/dump/timeZones.txt',
        ),
        'update_files' => [
            'modifications' => 'http://download.geonames.org/export/dump/modifications-%s.txt',
            'deletes'       => 'http://download.geonames.org/export/dump/deletes-%s.txt',
        ],

        'cities_files' => [
            'cities_small'  => 'http://download.geonames.org/export/dump/cities1000.zip',
            'cities_medium' => 'http://download.geonames.org/export/dump/cities5000.zip',
            'cities_large'  => 'http://download.geonames.org/export/dump/cities15000.zip',
        ],

        'development' => 'http://download.geonames.org/export/dump/cities15000.zip',

		'wildcard' => 'http://download.geonames.org/export/dump/%s.zip',
	),

);