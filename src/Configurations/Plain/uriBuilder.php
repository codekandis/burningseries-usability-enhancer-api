<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Configurations\Plain;

return [
	'relativeUris' => [
		'index'               => '',
		'users'               => 'users',
		'user'                => 'users/%s',
		'seriesDenials'       => 'series-denials',
		'seriesDenial'        => 'series-denials/%s',
		'seriesDenialUsers'   => 'series-denials/%s/users',
		'userSeriesDenials'   => 'users/%s/series-denials',
		'seriesInterests'     => 'series-interests',
		'seriesInterest'      => 'series-interests/%s',
		'seriesInterestUsers' => 'series-interests/%s/users',
		'userSeriesInterests' => 'users/%s/series-interests',
		'seriesFavorites'     => 'series-favorites',
		'seriesFavorite'      => 'series-favorites/%s',
		'seriesFavoriteUsers' => 'series-favorites/%s/users',
		'userSeriesFavorites' => 'users/%s/series-favorites'
	]
];
