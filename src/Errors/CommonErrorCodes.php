<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors;

abstract class CommonErrorCodes
{
	public const INTERNAL_ERROR         = 1;

	public const INVALID_CONTENT_TYPE   = 2;

	public const MALFORMED_REQUEST_BODY = 3;

	public const INVALID_REQUEST_BODY   = 4;
}
