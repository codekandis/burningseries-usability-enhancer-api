<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Put;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\CommonErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\CommonErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\UsersErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\UsersErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesDenialsRepository;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesFavoritesRepository;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesInterestsRepository;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesWatchedRepository;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\UsersRepository;
use CodeKandis\Tiphy\Http\ContentTypes;
use CodeKandis\Tiphy\Http\Requests\BadRequestException;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;
use ReflectionException;
use function is_object;
use function strtolower;

class UserSeriesFavoriteAction extends AbstractWithDatabaseConnectorAction
{
	/**
	 * @throws PersistenceException
	 * @throws ReflectionException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		try
		{
			$inputData = $this->getInputData();
		}
		catch ( BadRequestException $exception )
		{
			$errorInformation = new ErrorInformation( $exception->getCode(), $exception->getMessage() );
			( new JsonResponder( StatusCodes::BAD_REQUEST, null, $errorInformation ) )
				->respond();

			return;
		}

		$requestedUser     = new UserEntity();
		$requestedUser->id = $inputData[ 'userId' ];
		$user              = $this->readUserById( $requestedUser );

		if ( null === $user )
		{
			$errorInformation = new ErrorInformation( UsersErrorCodes::USER_UNKNOWN, UsersErrorMessages::USER_UNKNOWN );
			( new JsonResponder( StatusCodes::BAD_REQUEST, null, $errorInformation ) )
				->respond();

			return;
		}

		/**
		 * @var SeriesEntity $seriesFavorite
		 */
		$seriesFavorite = SeriesEntity::fromObject( $inputData[ 'seriesFavorite' ] );

		$seriesDenial = $this->readSeriesDenialByNameAndUserId( $seriesFavorite, $user );
		if ( null !== $seriesDenial )
		{
			$this->deleteSeriesDenialByIdAndUserId( $seriesDenial, $user );
		}

		$seriesInterest = $this->readSeriesInterestByNameAndUserId( $seriesFavorite, $user );
		if ( null !== $seriesInterest )
		{
			$this->deleteSeriesInterestByIdAndUserId( $seriesInterest, $user );
		}

		$seriesWatch = $this->readSeriesWatchByNameAndUserId( $seriesFavorite, $user );
		if ( null !== $seriesWatch )
		{
			$this->deleteSeriesWatchByIdAndUserId( $seriesWatch, $user );
		}

		$this->writeSeriesFavoriteByNameAndUserId( $seriesFavorite, $user );

		( new JsonResponder( StatusCodes::OK, null ) )
			->respond();
	}

	/**
	 * @throws BadRequestException
	 */
	private function getInputData(): array
	{
		if ( ContentTypes::APPLICATION_JSON !== strtolower( $_SERVER[ 'CONTENT_TYPE' ] ) )
		{
			throw new BadRequestException( CommonErrorMessages::INVALID_CONTENT_TYPE, CommonErrorCodes::INVALID_CONTENT_TYPE );
		}
		$requestBody = $this->requestBody->getContent();

		$isValid = is_object( $requestBody );
		if ( false === $isValid )
		{
			throw new BadRequestException( CommonErrorMessages::MALFORMED_REQUEST_BODY, CommonErrorCodes::MALFORMED_REQUEST_BODY );
		}

		$bodyData     = [];
		$requiredKeys = [
			'seriesFavorite'
		];

		$isValid = true;
		foreach ( $requiredKeys as $requiredKey )
		{
			$isValid = $isValid && isset( $requestBody->{$requiredKey} );
			if ( false === $isValid )
			{
				break;
			}
			$bodyData[ $requiredKey ] = $requestBody->{$requiredKey};
		}
		if ( false === $isValid )
		{
			throw new BadRequestException( CommonErrorMessages::INVALID_REQUEST_BODY, CommonErrorCodes::INVALID_REQUEST_BODY );
		}

		$argumentsData = $this->arguments;

		return $bodyData + $argumentsData;
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
	 * @throws PersistenceException
	 */
	private function readSeriesDenialByNameAndUserId( SeriesEntity $requestedSeriesDenial, UserEntity $requestedUser ): ?SeriesEntity
	{
		return ( new SeriesDenialsRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesDenialByNameAndUserId( $requestedSeriesDenial, $requestedUser );
	}

	/**
	 * @throws PersistenceException
	 */
	private function deleteSeriesDenialByIdAndUserId( SeriesEntity $requestedSeriesDenial, UserEntity $requestedUser ): void
	{
		( new SeriesDenialsRepository(
			$this->getDatabaseConnector()
		) )
			->deleteSeriesDenialByIdAndUserId( $requestedSeriesDenial, $requestedUser );
	}

	/**
	 * @throws PersistenceException
	 */
	private function readSeriesInterestByNameAndUserId( SeriesEntity $requestedSeriesInterest, UserEntity $requestedUser ): ?SeriesEntity
	{
		return ( new SeriesInterestsRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesInterestByNameAndUserId( $requestedSeriesInterest, $requestedUser );
	}

	/**
	 * @throws PersistenceException
	 */
	private function deleteSeriesInterestByIdAndUserId( SeriesEntity $requestedSeriesInterest, UserEntity $requestedUser ): void
	{
		( new SeriesInterestsRepository(
			$this->getDatabaseConnector()
		) )
			->deleteSeriesInterestByIdAndUserId( $requestedSeriesInterest, $requestedUser );
	}

	/**
	 * @throws PersistenceException
	 */
	private function readSeriesWatchByNameAndUserId( SeriesEntity $requestedSeriesWatch, UserEntity $requestedUser ): ?SeriesEntity
	{
		return ( new SeriesWatchedRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesWatchByNameAndUserId( $requestedSeriesWatch, $requestedUser );
	}

	/**
	 * @throws PersistenceException
	 */
	private function deleteSeriesWatchByIdAndUserId( SeriesEntity $requestedSeriesWatch, UserEntity $requestedUser ): void
	{
		( new SeriesWatchedRepository(
			$this->getDatabaseConnector()
		) )
			->deleteSeriesWatchByIdAndUserId( $requestedSeriesWatch, $requestedUser );
	}

	/**
	 * @throws PersistenceException
	 */
	private function writeSeriesFavoriteByNameAndUserId( SeriesEntity $seriesFavorite, UserEntity $user ): void
	{
		( new SeriesFavoritesRepository(
			$this->getDatabaseConnector()
		) )
			->writeSeriesFavoriteByNameAndUserId( $seriesFavorite, $user );
	}
}
