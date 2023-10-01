<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\UserApiUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesWatchedErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesWatchedErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesWatchedRepository;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\UsersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class SeriesWatchUsersAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
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

		$users = $this->readUsersBySeriesWatchId( $seriesWatch );
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
	private function readSeriesWatchById( SeriesEntity $seriesWatch ): ?SeriesEntity
	{
		return ( new SeriesWatchedRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesWatchById( $seriesWatch );
	}

	/**
	 * @return UserEntity[]
	 * @throws PersistenceException
	 */
	private function readUsersBySeriesWatchId( SeriesEntity $seriesWatch ): array
	{
		return ( new UsersRepository(
			$this->getDatabaseConnector()
		) )
			->readUsersBySeriesWatchId( $seriesWatch );
	}
}
