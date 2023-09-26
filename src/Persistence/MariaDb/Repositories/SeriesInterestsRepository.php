<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\Tiphy\Persistence\MariaDb\PreparedStatementInArrayHelper;
use CodeKandis\Tiphy\Persistence\MariaDb\Repositories\AbstractRepository;
use CodeKandis\Tiphy\Persistence\PersistenceException;

class SeriesInterestsRepository extends AbstractRepository
{
	/**
	 * @return SeriesEntity[]
	 * @throws PersistenceException
	 */
	public function readSeriesInterests(): array
	{
		$query = <<< END
			SELECT
				`seriesInterests`.*
			FROM
				`seriesInterests`
			ORDER BY
				`seriesInterests`.`createdOn` DESC;
		END;

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var SeriesEntity[] $resultSet */
			$resultSet = $this->databaseConnector->query( $query, null, SeriesEntity::class );
			$this->databaseConnector->commit();
		}
		catch ( PersistenceException $exception )
		{
			$this->databaseConnector->rollback();
			throw $exception;
		}

		return $resultSet;
	}

	/**
	 * @throws PersistenceException
	 */
	public function readSeriesInterestById( SeriesEntity $seriesInterest ): ?SeriesEntity
	{
		$query = <<< END
			SELECT
				`seriesInterests`.*
			FROM
				`seriesInterests`
			WHERE
				`seriesInterests`.`id` = :id
			LIMIT
				0, 1;
		END;

		$arguments = [
			'id' => $seriesInterest->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var SeriesEntity $result */
			$result = $this->databaseConnector->queryFirst( $query, $arguments, SeriesEntity::class );
			$this->databaseConnector->commit();
		}
		catch ( PersistenceException $exception )
		{
			$this->databaseConnector->rollback();
			throw $exception;
		}

		return $result;
	}

	/**
	 * @return SeriesEntity[]
	 * @throws PersistenceException
	 */
	public function readSeriesInterestsByUserId( UserEntity $user ): array
	{
		$query = <<< END
			SELECT
				`seriesInterests`.*
			FROM
				`seriesInterests`
			INNER JOIN
				`users_seriesInterests`
				ON
				`users_seriesInterests`.`userId` = :userId
			WHERE
				`seriesInterests`.`id` = `users_seriesInterests`.`seriesInterestId`
			ORDER BY
				`seriesInterests`.`createdOn` DESC;
		END;

		$arguments = [
			'userId' => $user->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var SeriesEntity[] $resultSet */
			$resultSet = $this->databaseConnector->query( $query, $arguments, SeriesEntity::class );
			$this->databaseConnector->commit();
		}
		catch ( PersistenceException $exception )
		{
			$this->databaseConnector->rollback();
			throw $exception;
		}

		return $resultSet;
	}

	/**
	 * @param SeriesEntity[] $series
	 * @return SeriesEntity[]
	 * @throws PersistenceException
	 */
	public function readSeriesInterestsFilteredByNamesAndUserId( array $series, UserEntity $user ): array
	{
		$inArrayHelper = new PreparedStatementInArrayHelper(
			'seriesName',
			array_map(
				fn( SeriesEntity $series ) => $series->name,
				$series
			)
		);

		$query = <<< END
			SELECT
				`seriesInterests`.*
			FROM
				`seriesInterests`
			INNER JOIN
				`users_seriesInterests`
				ON
				`users_seriesInterests`.`userId` = :userId
			WHERE
			    `seriesInterests`.name IN ( {$inArrayHelper->getNamedPlaceholders()} )
			    AND
				`seriesInterests`.`id` = `users_seriesInterests`.`seriesInterestId`
			ORDER BY
				`seriesInterests`.`createdOn` DESC;
		END;

		$arguments = [
						 'userId' => $user->id
					 ]
					 + $inArrayHelper->getArguments();

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var SeriesEntity[] $resultSet */
			$resultSet = $this->databaseConnector->query( $query, $arguments, SeriesEntity::class );
			$this->databaseConnector->commit();
		}
		catch ( PersistenceException $exception )
		{
			$this->databaseConnector->rollback();
			throw $exception;
		}

		return $resultSet;
	}

	/**
	 * @throws PersistenceException
	 */
	public function writeSeriesInterestByNameAndUserId( SeriesEntity $seriesInterestEntity, UserEntity $user ): void
	{
		$query = <<< END
			INSERT INTO
				`seriesInterests`
				( `id`, `name`, `uri`, `createdOn` )
			VALUES
				( UUID( ), LOWER( :name ), :uri, :createdOn )
			ON DUPLICATE KEY UPDATE
				`createdOn` = IF ( `createdOn` IS NULL OR `createdOn` > :createdOn, :createdOn, `createdOn` );

			INSERT IGNORE INTO
				`users_seriesInterests`
				( `id`, `userId`, `seriesInterestId`)
			SELECT
				UUID( ),
				:userId,
				`seriesInterests`.`id`
			FROM
				`seriesInterests`
			WHERE
				`seriesInterests`.`name` = :name;
		END;

		$arguments = [
			'userId'    => $user->id,
			'name'      => $seriesInterestEntity->name,
			'uri'       => $seriesInterestEntity->uri,
			'createdOn' => $seriesInterestEntity->createdOn
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			$this->databaseConnector->execute( $query, $arguments );
			$this->databaseConnector->commit();
		}
		catch ( PersistenceException $exception )
		{
			$this->databaseConnector->rollback();
			throw $exception;
		}
	}

	/**
	 * @throws PersistenceException
	 */
	public function deleteSeriesInterestByIdAndUserId( SeriesEntity $seriesInterest, UserEntity $user ): void
	{
		$query = <<< END
			DELETE
			FROM
				`users_seriesInterests`
			WHERE
				`users_seriesInterests`.`userId` = :userId
				AND
				`users_seriesInterests`.`seriesInterestId` = :id;

			DELETE
				`seriesInterests`
			FROM
				`seriesInterests`
			LEFT JOIN
				`users_seriesInterests`
			ON
				`users_seriesInterests`.`seriesInterestId` = `seriesInterests`.`id`
			WHERE
				`users_seriesInterests`.`seriesInterestId` IS NULL;
			
		END;

		$arguments = [
			'userId' => $user->id,
			'id'     => $seriesInterest->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			$this->databaseConnector->execute( $query, $arguments );
			$this->databaseConnector->commit();
		}
		catch ( PersistenceException $exception )
		{
			$this->databaseConnector->rollback();
			throw $exception;
		}
	}
}
