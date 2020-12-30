<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api;

use CodeKandis\Tiphy\Actions\ActionInterface;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Http\Responses\StatusMessages;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

/**
 * Represents the default action if the client is unauthorized.
 * @package codekandis/burningseries-usability-enhancer-api
 * @author Christian Ramelow <info@codekandis.net>
 */
class UnauthorizedAction implements ActionInterface
{
	/**
	 * @inheritDoc
	 * @throws JsonException An error occurred during the creation of the JSON response.
	 */
	public function execute(): void
	{
		$errorInformation = new ErrorInformation( StatusCodes::UNAUTHORIZED, StatusMessages::UNAUTHORIZED );
		( new JsonResponder( StatusCodes::UNAUTHORIZED, null, $errorInformation ) )
			->respond();
	}
}
