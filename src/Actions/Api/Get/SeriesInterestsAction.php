<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\SeriesInterestApiUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesInterestsRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use JsonException;

class SeriesInterestsAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$seriesInterests = $this->readSeriesInterests();
		$this->extendUris( $seriesInterests );

		$responderData = [
			'seriesInterests' => $seriesInterests,
		];
		( new JsonResponder( StatusCodes::OK, $responderData ) )
			->respond();
	}

	/**
	 * @param SeriesEntity[] $seriesInterests
	 */
	private function extendUris( array $seriesInterests ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $seriesInterests as $seriesInterest )
		{
			( new SeriesInterestApiUriExtender( $apiUriBuilder, $seriesInterest ) )
				->extend();
		}
	}

	/**
	 * @return SeriesEntity[]
	 * @throws PersistenceException
	 */
	private function readSeriesInterests(): array
	{
		return ( new SeriesInterestsRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesInterests();
	}
}
