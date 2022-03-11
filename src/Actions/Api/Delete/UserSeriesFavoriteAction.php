<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Delete;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesFavoritesErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesFavoritesErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\UsersErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\UsersErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesFavoritesRepository;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\UsersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class UserSeriesFavoriteAction extends AbstractWithDatabaseConnectorAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$inputData = $this->getInputData();

		$requestedUser     = new UserEntity();
		$requestedUser->id = $inputData[ 'userId' ];
		$user              = $this->readUserById( $requestedUser );

		if ( null === $user )
		{
			$errorInformation = new ErrorInformation( UsersErrorCodes::USER_UNKNOWN, UsersErrorMessages::USER_UNKNOWN, $inputData );
			( new JsonResponder( StatusCodes::NOT_FOUND, null, $errorInformation ) )
				->respond();

			return;
		}

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

		$this->deleteSeriesFavoriteByUserId( $user, $seriesFavorite );

		( new JsonResponder( StatusCodes::OK, null ) )
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
	 * @throws PersistenceException
	 */
	private function readUserById( UserEntity $requestedUser ): ?UserEntity
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new UsersRepository( $databaseConnector ) )
			->readUserById( $requestedUser );
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

	/**
	 * @throws PersistenceException
	 */
	private function deleteSeriesFavoriteByUserId( UserEntity $user, SeriesEntity $seriesFavorite ): void
	{
		( new SeriesFavoritesRepository(
			$this->getDatabaseConnector()
		) )
			->deleteSeriesFavoriteByUserId( $seriesFavorite, $user );
	}
}
