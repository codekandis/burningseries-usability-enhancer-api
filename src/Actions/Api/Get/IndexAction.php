<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithApiUriBuilderAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\IndexEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\IndexApiUriExtender;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use JsonException;

class IndexAction extends AbstractWithApiUriBuilderAction
{
	/**
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$index = new IndexEntity;
		$this->extendUris( $index );

		$responderData = [
			'index' => $index,
		];
		( new JsonResponder( StatusCodes::OK, $responderData ) )
			->respond();
	}

	private function extendUris( $index ): void
	{
		( new IndexApiUriExtender(
			$this->getApiUriBuilder(),
			$index
		) )
			->extend();
	}
}
