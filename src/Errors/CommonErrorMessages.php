<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors;

abstract class CommonErrorMessages
{
	public const INTERNAL_ERROR         = 'An internal error occurred.';

	public const INVALID_CONTENT_TYPE   = 'The request content type is invalid.';

	public const MALFORMED_REQUEST_BODY = 'The request body is malformed.';

	public const INVALID_REQUEST_BODY   = 'The request body is invalid.';
}
