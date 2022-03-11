<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\SeriesInterestApiUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesInterestsErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesInterestsErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesInterestsRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class SeriesInterestAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$inputData = $this->getInputData();

		$requestedSeriesInterest     = new SeriesEntity();
		$requestedSeriesInterest->id = $inputData[ 'seriesInterestId' ];
		$seriesInterest              = $this->readSeriesInterestById( $requestedSeriesInterest );

		if ( null === $seriesInterest )
		{
			$errorInformation = new ErrorInformation( SeriesInterestsErrorCodes::SERIES_UNKNOWN, SeriesInterestsErrorMessages::SERIES_UNKNOWN, $inputData );
			( new JsonResponder( StatusCodes::NOT_FOUND, null, $errorInformation ) )
				->respond();

			return;
		}

		$this->extendUris( $seriesInterest );

		$responderData = [
			'seriesInterest' => $seriesInterest
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

	private function extendUris( SeriesEntity $seriesInterest ): void
	{
		( new SeriesInterestApiUriExtender(
			$this->getApiUriBuilder(),
			$seriesInterest
		) )
			->extend();
	}

	/**
	 * @throws PersistenceException
	 */
	private function readSeriesInterestById( SeriesEntity $requestedSeriesInterest ): ?SeriesEntity
	{
		return ( new SeriesInterestsRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesInterestById( $requestedSeriesInterest );
	}
}
