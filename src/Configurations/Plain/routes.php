<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Configurations\Plain;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api;
use CodeKandis\Tiphy\Http\Requests\Methods;

return [
	'routes' => [
		'^/$'                                                                                                                                           => [
			Methods::GET => Api\Get\IndexAction::class
		],
		'^/users$'                                                                                                                                      => [
			Methods::GET => Api\Get\UsersAction::class
		],
		'^/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})$'                                                                               => [
			Methods::GET => Api\Get\UserAction::class
		],
		'^/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-denials$'                                                                => [
			Methods::GET => Api\Get\UserSeriesDenialsAction::class,
			Methods::PUT => Api\Put\UserSeriesDenialAction::class
		],
		'^/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-denials/filtered$'                                                       => [
			Methods::PUT => Api\Put\UserSeriesDenialsFilteredAction::class
		],
		'^/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-denials/plain$'                                                          => [
			Methods::PUT => Api\Put\UserSeriesDenialsAction::class
		],
		'^/users/(?<userId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/series-denials/(?<seriesDenialId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})$' => [
			Methods::DELETE => Api\Delete\UserSeriesDenialAction::class
		],
		'^/series-denials$'                                                                                                                             => [
			Methods::GET => Api\Get\SeriesDenialsAction::class
		],
		'^/series-denials/(?<seriesDenialId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})$'                                                              => [
			Methods::GET => Api\Get\SeriesDenialAction::class
		],
		'^/series-denials/(?<seriesDenialId>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})/users$'                                                        => [
			Methods::GET => Api\Get\SeriesDenialUsersAction::class
		]
	]
];
