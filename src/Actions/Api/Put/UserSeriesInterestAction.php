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

class UserSeriesInterestAction extends AbstractWithDatabaseConnectorAction
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
		 * @var SeriesEntity $seriesInterest
		 */
		$seriesInterest = SeriesEntity::fromObject( $inputData[ 'seriesInterest' ] );

		$seriesDenial = $this->readSeriesDenialByNameAndUserId( $seriesInterest, $user );
		if ( null !== $seriesDenial )
		{
			$this->deleteSeriesDenialByIdAndUserId( $seriesDenial, $user );
		}

		$seriesFavorite = $this->readSeriesFavoriteByNameAndUserId( $seriesInterest, $user );
		if ( null !== $seriesFavorite )
		{
			$this->deleteSeriesFavoriteByIdAndUserId( $seriesFavorite, $user );
		}

		$seriesWatch = $this->readSeriesWatchByNameAndUserId( $seriesInterest, $user );
		if ( null !== $seriesWatch )
		{
			$this->deleteSeriesWatchByIdAndUserId( $seriesWatch, $user );
		}

		$this->writeSeriesInterestByNameAndUserId( $seriesInterest, $user );

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
			'seriesInterest'
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
	private function readSeriesFavoriteByNameAndUserId( SeriesEntity $requestedSeriesFavorite, UserEntity $requestedUser ): ?SeriesEntity
	{
		return ( new SeriesFavoritesRepository(
			$this->getDatabaseConnector()
		) )
			->readSeriesFavoriteByNameAndUserId( $requestedSeriesFavorite, $requestedUser );
	}

	/**
	 * @throws PersistenceException
	 */
	private function deleteSeriesFavoriteByIdAndUserId( SeriesEntity $requestedSeriesFavorite, UserEntity $requestedUser ): void
	{
		( new SeriesFavoritesRepository(
			$this->getDatabaseConnector()
		) )
			->deleteSeriesFavoriteByIdAndUserId( $requestedSeriesFavorite, $requestedUser );
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
	private function writeSeriesInterestByNameAndUserId( SeriesEntity $seriesInterest, UserEntity $user ): void
	{
		( new SeriesInterestsRepository(
			$this->getDatabaseConnector()
		) )
			->writeSeriesInterestByNameAndUserId( $seriesInterest, $user );
	}
}
