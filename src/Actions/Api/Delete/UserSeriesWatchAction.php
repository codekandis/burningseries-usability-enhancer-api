<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Delete;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesWatchedErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesWatchedErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\UsersErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\UsersErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesWatchedRepository;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\UsersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class UserSeriesWatchAction extends AbstractWithDatabaseConnectorAction
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

		$this->deleteSeriesWatchByIdAndUserId( $user, $seriesWatch );

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
	private function readSeriesWatchById( SeriesEntity $requestedSeriesWatch ): ?SeriesEntity
	{
		return ( new SeriesWatchedRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesWatchById( $requestedSeriesWatch );
	}

	/**
	 * @throws PersistenceException
	 */
	private function deleteSeriesWatchByIdAndUserId( UserEntity $user, SeriesEntity $seriesWatch ): void
	{
		( new SeriesWatchedRepository(
			$this->getDatabaseConnector()
		) )
			->deleteSeriesWatchByIdAndUserId( $seriesWatch, $user );
	}
}
