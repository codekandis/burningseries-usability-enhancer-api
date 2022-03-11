<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\SeriesFavoriteApiUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesFavoritesRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use JsonException;

class SeriesFavoritesAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$seriesFavorites = $this->readSeriesFavorites();
		$this->extendUris( $seriesFavorites );

		$responderData = [
			'seriesFavorites' => $seriesFavorites,
		];
		( new JsonResponder( StatusCodes::OK, $responderData ) )
			->respond();
	}

	/**
	 * @param SeriesEntity[] $seriesFavorites
	 */
	private function extendUris( array $seriesFavorites ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $seriesFavorites as $seriesFavorite )
		{
			( new SeriesFavoriteApiUriExtender( $apiUriBuilder, $seriesFavorite ) )
				->extend();
		}
	}

	/**
	 * @return SeriesEntity[]
	 * @throws PersistenceException
	 */
	private function readSeriesFavorites(): array
	{
		return ( new SeriesFavoritesRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesFavorites();
	}
}
