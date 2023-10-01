<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\SeriesWatchApiUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesWatchedErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesWatchedErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesWatchedRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class SeriesWatchAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$inputData = $this->getInputData();

		$requestedSeriesWatch     = new SeriesEntity();
		$requestedSeriesWatch->id = $inputData[ 'seriesWatchId' ];
		$seriesWatch              = $this->readSeriesWatchById( $requestedSeriesWatch );

		if ( null === $seriesWatch )
		{
			$errorInformation = new ErrorInformation( SeriesWatchedErrorCodes::SERIES_UNKNOWN, SeriesWatchedErrorMessages::SERIES_UNKNOWN, $inputData );
			( new JsonResponder( StatusCodes::NOT_FOUND, null, $errorInformation ) )
				->respond();

			return;
		}

		$this->extendUris( $seriesWatch );

		$responderData = [
			'seriesWatch' => $seriesWatch
		];
		( new JsonResponder( StatusCodes::OK, $responderData ) )
			->respond();
	}

	/**
	 * @return string[]
	 */
	private function getInputData(): array
	{
		return $this->arguments;
	}

	private function extendUris( SeriesEntity $seriesWatch ): void
	{
		( new SeriesWatchApiUriExtender(
			$this->getApiUriBuilder(),
			$seriesWatch
		) )
			->extend();
	}

	/**
	 * @throws PersistenceException
	 */
	private function readSeriesWatchById( SeriesEntity $requestedSeriesWatch ): ?SeriesEntity
	{
		return ( new SeriesWatchedRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesWatchById( $requestedSeriesWatch );
	}
}
