<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\SeriesWatchApiUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesWatchedRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use JsonException;

class SeriesWatchedAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$seriesWatched = $this->readSeriesWatched();
		$this->extendUris( $seriesWatched );

		$responderData = [
			'seriesWatched' => $seriesWatched,
		];
		( new JsonResponder( StatusCodes::OK, $responderData ) )
			->respond();
	}

	/**
	 * @param SeriesEntity[] $seriesWatched
	 */
	private function extendUris( array $seriesWatched ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $seriesWatched as $seriesWatch )
		{
			( new SeriesWatchApiUriExtender( $apiUriBuilder, $seriesWatch ) )
				->extend();
		}
	}

	/**
	 * @return SeriesEntity[]
	 * @throws PersistenceException
	 */
	private function readSeriesWatched(): array
	{
		return ( new SeriesWatchedRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesWatched();
	}
}
