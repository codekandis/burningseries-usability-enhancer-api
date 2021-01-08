<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Put;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Configurations\ConfigurationRegistry;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\CommonErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\CommonErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\UsersErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\UsersErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesDenialsRepository;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\UsersRepository;
use CodeKandis\Tiphy\Actions\AbstractAction;
use CodeKandis\Tiphy\Http\ContentTypes;
use CodeKandis\Tiphy\Http\Requests\BadRequestException;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\MariaDb\Connector;
use CodeKandis\Tiphy\Persistence\MariaDb\ConnectorInterface;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;
use ReflectionException;
use function array_map;
use function is_object;
use function strtolower;

class UserSeriesDenialsFilteredAction extends AbstractAction
{
	/** @var ConnectorInterface */
	private ConnectorInterface $databaseConnector;

	private function getDatabaseConnector(): ConnectorInterface
	{
		return $this->databaseConnector
			   ?? $this->databaseConnector =
				   new Connector(
					   ConfigurationRegistry::_()
											->getPersistenceConfiguration()
				   );
	}

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
		$user              = $this->readUser( $requestedUser );

		if ( null === $user )
		{
			$errorInformation = new ErrorInformation( UsersErrorCodes::USER_UNKNOWN, UsersErrorMessages::USER_UNKNOWN );
			( new JsonResponder( StatusCodes::BAD_REQUEST, null, $errorInformation ) )
				->respond();

			return;
		}

		/**
		 * @var SeriesEntity[] $series
		 */
		$series = array_map(
			fn( $sentSeries ) => SeriesEntity::fromObject( $sentSeries ),
			$inputData[ 'series' ]
		);

		$responderData = [
			'seriesDenials' => $this->readSeriesDenialsFilteredByUserId( $series, $user )
		];

		( new JsonResponder( StatusCodes::OK, $responderData ) )
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
			'series'
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
	private function readUser( UserEntity $requestedUser ): ?UserEntity
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new UsersRepository( $databaseConnector ) )
			->readUserById( $requestedUser );
	}

	/**
	 * @param SeriesEntity[] $series
	 * @return SeriesEntity[]
	 * @throws PersistenceException
	 */
	private function readSeriesDenialsFilteredByUserId( array $series, UserEntity $user ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new SeriesDenialsRepository( $databaseConnector ) )
			->readSeriesDenialsFilteredByUserId( $series, $user );
	}
}
