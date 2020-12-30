<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Configurations\ConfigurationRegistry;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\UserUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilder;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilderInterface;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\UsersRepository;
use CodeKandis\Tiphy\Actions\AbstractAction;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\MariaDb\Connector;
use CodeKandis\Tiphy\Persistence\MariaDb\ConnectorInterface;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use JsonException;

class UsersAction extends AbstractAction
{
	/** @var ConnectorInterface */
	private ConnectorInterface $databaseConnector;

	/** @var ApiUriBuilderInterface */
	private ApiUriBuilderInterface $uriBuilder;

	private function getDatabaseConnector(): ConnectorInterface
	{
		return $this->databaseConnector
			   ?? $this->databaseConnector =
				   new Connector(
					   ConfigurationRegistry::_()
											->getPersistenceConfiguration()
				   );
	}

	private function getUriBuilder(): ApiUriBuilderInterface
	{
		return $this->uriBuilder
			   ?? $this->uriBuilder =
				   new ApiUriBuilder(
					   ConfigurationRegistry::_()->getUriBuilderConfiguration()
				   );
	}

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
		$uriBuilder = $this->getUriBuilder();
		foreach ( $users as $user )
		{
			( new UserUriExtender( $uriBuilder, $user ) )
				->extend();
		}
	}

	/**
	 * @return UserEntity[]
	 * @throws PersistenceException
	 */
	private function readUsers(): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new UsersRepository( $databaseConnector ) )
			->readUsers();
	}
}
