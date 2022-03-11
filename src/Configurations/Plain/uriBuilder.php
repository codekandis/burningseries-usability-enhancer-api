<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Configurations\Plain;

return [
	'relativeUris' => [
		'index'               => '',
		'users'               => 'users',
		'user'                => 'users/%s',
		'userSeriesDenials'   => 'users/%s/series-denials',
		'seriesDenials'       => 'series-denials',
		'seriesDenial'        => 'series-denials/%s',
		'seriesDenialUsers'   => 'series-denials/%s/users',
		'userSeriesInterests' => 'users/%s/series-interests',
		'seriesInterests'     => 'series-interests',
		'seriesInterest'      => 'series-interests/%s',
		'seriesInterestUsers' => 'series-interests/%s/users',
		'userSeriesFavorites' => 'users/%s/series-favorites',
		'seriesFavorites'     => 'series-favorites',
		'seriesFavorite'      => 'series-favorites/%s',
		'seriesFavoriteUsers' => 'series-favorites/%s/users'
	]
];
