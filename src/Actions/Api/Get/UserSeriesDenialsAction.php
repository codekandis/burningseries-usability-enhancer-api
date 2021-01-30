<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\SeriesDenialApiUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\UsersErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\UsersErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesDenialsRepository;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\UsersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class UserSeriesDenialsAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
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

		$seriesDenials = $this->readSeriesDenialsByUserId( $user );
		$this->extendUris( $seriesDenials );

		$responderData = [
			'seriesDenials' => $seriesDenials,
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
	 * @param SeriesEntity[] $seriesDenials
	 */
	private function extendUris( array $seriesDenials ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $seriesDenials as $seriesDenial )
		{
			( new SeriesDenialApiUriExtender( $apiUriBuilder, $seriesDenial ) )
				->extend();
		}
	}

	/**
	 * @throws PersistenceException
	 */
	private function readUserById( UserEntity $requestedUser ): ?UserEntity
	{
		return ( new UsersRepository(
			$this->getDatabaseConnector()
		) )
			->readUserById( $requestedUser );
	}

	/**
	 * @return SeriesEntity[]
	 * @throws PersistenceException
	 */
	private function readSeriesDenialsByUserId( UserEntity $user ): array
	{
		return ( new SeriesDenialsRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesDenialsByUserId( $user );
	}
}
