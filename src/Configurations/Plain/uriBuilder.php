<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Configurations\Plain;

return [
	'relativeUris' => [
		'index'             => '',
		'users'             => 'users',
		'user'              => 'users/%s',
		'userSeriesDenials' => 'users/%s/series-denials',
		'seriesDenials'     => 'series-denials',
		'seriesDenial'      => 'series-denials/%s',
		'seriesDenialUsers' => 'series-denials/%s/users'
	]
];
