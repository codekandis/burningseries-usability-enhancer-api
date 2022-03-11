<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\UserApiUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesFavoritesErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesFavoritesErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesFavoritesRepository;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\UsersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class SeriesFavoriteUsersAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
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

		$users = $this->readUsersBySeriesFavoriteId( $seriesFavorite );
		$this->extendUris( $users );

		$responderData = [
			'users' => $users,
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

	/**
	 * @param UserEntity[] $users
	 */
	private function extendUris( array $users ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $users as $user )
		{
			( new UserApiUriExtender( $apiUriBuilder, $user ) )
				->extend();
		}
	}

	/**
	 * @throws PersistenceException
	 */
	private function readSeriesFavoriteById( SeriesEntity $seriesFavorite ): ?SeriesEntity
	{
		return ( new SeriesFavoritesRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesFavoriteById( $seriesFavorite );
	}

	/**
	 * @return UserEntity[]
	 * @throws PersistenceException
	 */
	private function readUsersBySeriesFavoriteId( SeriesEntity $seriesFavorite ): array
	{
		return ( new UsersRepository(
			$this->getDatabaseConnector()
		) )
			->readUsersBySeriesFavoriteId( $seriesFavorite );
	}
}
