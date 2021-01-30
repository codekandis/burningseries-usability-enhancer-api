<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\UserApiUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesDenialsErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesDenialsErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesDenialsRepository;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\UsersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class SeriesDenialUsersAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
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

		$users = $this->readUsersBySeriesDenialId( $seriesDenial );
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
	private function readSeriesDenialById( SeriesEntity $seriesDenial ): ?SeriesEntity
	{
		return ( new SeriesDenialsRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesDenialById( $seriesDenial );
	}

	/**
	 * @return UserEntity[]
	 * @throws PersistenceException
	 */
	private function readUsersBySeriesDenialId( SeriesEntity $seriesDenial ): array
	{
		return ( new UsersRepository(
			$this->getDatabaseConnector()
		) )
			->readUsersBySeriesDenialId( $seriesDenial );
	}
}
