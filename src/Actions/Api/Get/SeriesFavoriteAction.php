<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\SeriesFavoriteApiUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesFavoritesErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesFavoritesErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesFavoritesRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class SeriesFavoriteAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$inputData = $this->getInputData();

		$requestedSeriesFavorite     = new SeriesEntity();
		$requestedSeriesFavorite->id = $inputData[ 'seriesFavoriteId' ];
		$seriesFavorite              = $this->readSeriesFavoriteById( $requestedSeriesFavorite );

		if ( null === $seriesFavorite )
		{
			$errorInformation = new ErrorInformation( SeriesFavoritesErrorCodes::SERIES_UNKNOWN, SeriesFavoritesErrorMessages::SERIES_UNKNOWN, $inputData );
			( new JsonResponder( StatusCodes::NOT_FOUND, null, $errorInformation ) )
				->respond();

			return;
		}

		$this->extendUris( $seriesFavorite );

		$responderData = [
			'seriesFavorite' => $seriesFavorite
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

	private function extendUris( SeriesEntity $seriesFavorite ): void
	{
		( new SeriesFavoriteApiUriExtender(
			$this->getApiUriBuilder(),
			$seriesFavorite
		) )
			->extend();
	}

	/**
	 * @throws PersistenceException
	 */
	private function readSeriesFavoriteById( SeriesEntity $requestedSeriesFavorite ): ?SeriesEntity
	{
		return ( new SeriesFavoritesRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesFavoriteById( $requestedSeriesFavorite );
	}
}
