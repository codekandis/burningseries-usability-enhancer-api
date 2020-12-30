<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\Authentication;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\Authentication\UserEntity;
use CodeKandis\Tiphy\Persistence\MariaDb\Repositories\AbstractRepository;
use CodeKandis\Tiphy\Persistence\PersistenceException;

class UsersRepository extends AbstractRepository
{
	/**
	 * @throws PersistenceException
	 */
	public function readUserByKey( UserEntity $user ): ?UserEntity
	{
		$query = <<< END
			SELECT
				`users`.`id`,
				`users`.`isActive`,
				`users`.`name`,
				`users`.`email`,
				`apiKeys`.`key` as `apiKey`
			FROM
				`users`
			JOIN
				`apiKeys`
			ON
				`apiKeys`.`userId` = `users`.`id`
				AND
				`apiKeys`.`key` = :key
			LIMIT
				0, 1;
		END;

		$arguments = [
			'key' => $user->apiKey
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var UserEntity $result */
			$result = $this->databaseConnector->queryFirst( $query, $arguments, UserEntity::class );
			$this->databaseConnector->commit();
		}
		catch ( PersistenceException $exception )
		{
			$this->databaseConnector->rollback();
			throw $exception;
		}

		return $result;
	}
}
