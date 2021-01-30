<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\SeriesDenialApiUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesDenialsErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesDenialsErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesDenialsRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class SeriesDenialAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$inputData = $this->getInputData();

		$requestedSeriesDenial     = new SeriesEntity();
		$requestedSeriesDenial->id = $inputData[ 'seriesDenialId' ];
		$seriesDenial              = $this->readSeriesDenialById( $requestedSeriesDenial );

		if ( null === $seriesDenial )
		{
			$errorInformation = new ErrorInformation( SeriesDenialsErrorCodes::SERIES_UNKNOWN, SeriesDenialsErrorMessages::SERIES_UNKNOWN, $inputData );
			( new JsonResponder( StatusCodes::NOT_FOUND, null, $errorInformation ) )
				->respond();

			return;
		}

		$this->extendUris( $seriesDenial );

		$responderData = [
			'seriesDenial' => $seriesDenial
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

	private function extendUris( SeriesEntity $seriesDenial ): void
	{
		( new SeriesDenialApiUriExtender(
			$this->getApiUriBuilder(),
			$seriesDenial
		) )
			->extend();
	}

	/**
	 * @throws PersistenceException
	 */
	private function readSeriesDenialById( SeriesEntity $requestedSeriesDenial ): ?SeriesEntity
	{
		return ( new SeriesDenialsRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesDenialById( $requestedSeriesDenial );
	}
}
