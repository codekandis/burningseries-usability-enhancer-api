<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\SeriesDenialApiUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesDenialsRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use JsonException;

class SeriesDenialsAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$seriesDenials = $this->readSeriesDenials();
		$this->extendUris( $seriesDenials );

		$responderData = [
			'seriesDenials' => $seriesDenials,
		];
		( new JsonResponder( StatusCodes::OK, $responderData ) )
			->respond();
	}

	/**
	 * @param SeriesEntity[] $seriesDenials
	 */
	private function extendUris( array $seriesDenials ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $seriesDenials as $seriesDenial )
		{
			( new SeriesDenialApiUriExtender( $apiUriBuilder, $seriesDenial ) )
				->extend();
		}
	}

	/**
	 * @return SeriesEntity[]
	 * @throws PersistenceException
	 */
	private function readSeriesDenials(): array
	{
		return ( new SeriesDenialsRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesDenials();
	}
}
