<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\UserApiUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\UsersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use JsonException;

class UsersAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$users = $this->readUsers();
		$this->extendUris( $users );

		$responderData = [
			'users' => $users,
		];
		$responder     = new JsonResponder( StatusCodes::OK, $responderData );
		$responder->respond();
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
	 * @return UserEntity[]
	 * @throws PersistenceException
	 */
	private function readUsers(): array
	{
		return ( new UsersRepository(
			$this->getDatabaseConnector()
		) )
			->readUsers();
	}
}
