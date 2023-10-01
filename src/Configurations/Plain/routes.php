<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Configurations\Plain;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api;
use CodeKandis\Tiphy\Http\Requests\Methods;

return [
	'routes' => [
		'/'                                                                                                                                               => [
			Methods::GET => Api\Get\IndexAction::class
		],
		'/users'                                                                                                                                          => [
			Methods::GET => Api\Get\UsersAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})'                                                                                   => [
			Methods::GET => Api\Get\UserAction::class
		],
		'/series-denials'                                                                                                                                 => [
			Methods::GET => Api\Get\SeriesDenialsAction::class
		],
		'/series-denials/(?<seriesDenialId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})'                                                                  => [
			Methods::GET => Api\Get\SeriesDenialAction::class
		],
		'/series-denials/(?<seriesDenialId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/users'                                                            => [
			Methods::GET => Api\Get\SeriesDenialUsersAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-denials'                                                                    => [
			Methods::GET => Api\Get\UserSeriesDenialsAction::class,
			Methods::PUT => Api\Put\UserSeriesDenialAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-denials/filtered'                                                           => [
			Methods::PUT => Api\Put\UserSeriesDenialsFilteredAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-denials/plain'                                                              => [
			Methods::PUT => Api\Put\UserSeriesDenialsAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-denials/(?<seriesDenialId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})'     => [
			Methods::DELETE => Api\Delete\UserSeriesDenialAction::class
		],
		'/series-interests'                                                                                                                               => [
			Methods::GET => Api\Get\SeriesInterestsAction::class
		],
		'/series-interests/(?<seriesInterestId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})'                                                              => [
			Methods::GET => Api\Get\SeriesInterestAction::class
		],
		'/series-interests/(?<seriesInterestId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/users'                                                        => [
			Methods::GET => Api\Get\SeriesInterestUsersAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-interests'                                                                  => [
			Methods::GET => Api\Get\UserSeriesInterestsAction::class,
			Methods::PUT => Api\Put\UserSeriesInterestAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-interests/filtered'                                                         => [
			Methods::PUT => Api\Put\UserSeriesInterestsFilteredAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-interests/plain'                                                            => [
			Methods::PUT => Api\Put\UserSeriesInterestsAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-interests/(?<seriesInterestId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})' => [
			Methods::DELETE => Api\Delete\UserSeriesInterestAction::class
		],
		'/series-favorites'                                                                                                                               => [
			Methods::GET => Api\Get\SeriesFavoritesAction::class
		],
		'/series-favorites/(?<seriesFavoriteId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})'                                                              => [
			Methods::GET => Api\Get\SeriesFavoriteAction::class
		],
		'/series-favorites/(?<seriesFavoriteId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/users'                                                        => [
			Methods::GET => Api\Get\SeriesFavoriteUsersAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-favorites'                                                                  => [
			Methods::GET => Api\Get\UserSeriesFavoritesAction::class,
			Methods::PUT => Api\Put\UserSeriesFavoriteAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-favorites/filtered'                                                         => [
			Methods::PUT => Api\Put\UserSeriesFavoritesFilteredAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-favorites/plain'                                                            => [
			Methods::PUT => Api\Put\UserSeriesFavoritesAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-favorites/(?<seriesFavoriteId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})' => [
			Methods::DELETE => Api\Delete\UserSeriesFavoriteAction::class
		],
		'/series-watched'                                                                                                                                 => [
			Methods::GET => Api\Get\SeriesWatchedAction::class
		],
		'/series-watched/(?<seriesWatchId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})'                                                                   => [
			Methods::GET => Api\Get\SeriesWatchAction::class
		],
		'/series-watched/(?<seriesWatchId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/users'                                                             => [
			Methods::GET => Api\Get\SeriesWatchUsersAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-watched'                                                                    => [
			Methods::GET => Api\Get\UserSeriesWatchedAction::class,
			Methods::PUT => Api\Put\UserSeriesWatchAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-watched/filtered'                                                           => [
			Methods::PUT => Api\Put\UserSeriesWatchedFilteredAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-watched/plain'                                                              => [
			Methods::PUT => Api\Put\UserSeriesWatchedAction::class
		],
		'/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-watched/(?<seriesWatchId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})'      => [
			Methods::DELETE => Api\Delete\UserSeriesWatchAction::class
		]
	]
];
