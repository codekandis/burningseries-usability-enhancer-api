<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Delete;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesDenialsErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesDenialsErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\UsersErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\UsersErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesDenialsRepository;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\UsersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class UserSeriesDenialAction extends AbstractWithDatabaseConnectorAction
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

		$this->deleteSeriesDenialByUserId( $user, $seriesDenial );

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
	private function readSeriesDenialById( SeriesEntity $requestedSeriesDenial ): ?SeriesEntity
	{
		return ( new SeriesDenialsRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesDenialById( $requestedSeriesDenial );
	}

	/**
	 * @throws PersistenceException
	 */
	private function deleteSeriesDenialByUserId( UserEntity $user, SeriesEntity $seriesDenial ): void
	{
		( new SeriesDenialsRepository(
			$this->getDatabaseConnector()
		) )
			->deleteSeriesDenialByUserId( $seriesDenial, $user );
	}
}
