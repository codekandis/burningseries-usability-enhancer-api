<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\Tiphy\Persistence\MariaDb\PreparedStatementInArrayHelper;
use CodeKandis\Tiphy\Persistence\MariaDb\Repositories\AbstractRepository;
use CodeKandis\Tiphy\Persistence\PersistenceException;

class SeriesWatchedRepository extends AbstractRepository
{
	/**
	 * @return SeriesEntity[]
	 * @throws PersistenceException
	 */
	public function readSeriesWatched(): array
	{
		$query = <<< END
			SELECT
				`seriesWatched`.*
			FROM
				`seriesWatched`
			ORDER BY
				`seriesWatched`.`createdOn` DESC;
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
	public function readSeriesWatchById( SeriesEntity $seriesWatch ): ?SeriesEntity
	{
		$query = <<< END
			SELECT
				`seriesWatched`.*
			FROM
				`seriesWatched`
			WHERE
				`seriesWatched`.`id` = :id
			LIMIT
				0, 1;
		END;

		$arguments = [
			'id' => $seriesWatch->id
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
	 * @throws PersistenceException
	 */
	public function readSeriesWatchByNameAndUserId( SeriesEntity $seriesWatch, UserEntity $user ): ?SeriesEntity
	{
		$query = <<< END
			SELECT
				`seriesWatched`.*
			FROM
				`seriesWatched`
			INNER JOIN
				`users_seriesWatched`
				ON
				`users_seriesWatched`.`userId` = :userId
			WHERE
			    `seriesWatched`.name = :name
			    AND
				`seriesWatched`.`id` = `users_seriesWatched`.`seriesWatchId`
			LIMIT
				0, 1;
		END;

		$arguments = [
			'name'   => $seriesWatch->name,
			'userId' => $user->id,
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
	public function readSeriesWatchedByUserId( UserEntity $user ): array
	{
		$query = <<< END
			SELECT
				`seriesWatched`.*
			FROM
				`seriesWatched`
			INNER JOIN
				`users_seriesWatched`
				ON
				`users_seriesWatched`.`userId` = :userId
			WHERE
				`seriesWatched`.`id` = `users_seriesWatched`.`seriesWatchId`
			ORDER BY
				`seriesWatched`.`createdOn` DESC;
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
	public function readSeriesWatchedFilteredByNamesAndUserId( array $series, UserEntity $user ): array
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
				`seriesWatched`.*
			FROM
				`seriesWatched`
			INNER JOIN
				`users_seriesWatched`
				ON
				`users_seriesWatched`.`userId` = :userId
			WHERE
			    `seriesWatched`.name IN ( {$inArrayHelper->getNamedPlaceholders()} )
			    AND
				`seriesWatched`.`id` = `users_seriesWatched`.`seriesWatchId`
			ORDER BY
				`seriesWatched`.`createdOn` DESC;
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
	public function writeSeriesWatchByNameAndUserId( SeriesEntity $seriesWatchEntity, UserEntity $user ): void
	{
		$query = <<< END
			INSERT INTO
				`seriesWatched`
				( `id`, `name`, `uri`, `createdOn` )
			VALUES
				( UUID( ), LOWER( :name ), :uri, :createdOn )
			ON DUPLICATE KEY UPDATE
				`createdOn` = IF ( `createdOn` IS NULL OR `createdOn` > :createdOn, :createdOn, `createdOn` );

			INSERT IGNORE INTO
				`users_seriesWatched`
				( `id`, `userId`, `seriesWatchId`)
			SELECT
				UUID( ),
				:userId,
				`seriesWatched`.`id`
			FROM
				`seriesWatched`
			WHERE
				`seriesWatched`.`name` = :name;
		END;

		$arguments = [
			'userId'    => $user->id,
			'name'      => $seriesWatchEntity->name,
			'uri'       => $seriesWatchEntity->uri,
			'createdOn' => $seriesWatchEntity->createdOn
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
	public function deleteSeriesWatchByIdAndUserId( SeriesEntity $seriesWatch, UserEntity $user ): void
	{
		$query = <<< END
			DELETE
			FROM
				`users_seriesWatched`
			WHERE
				`users_seriesWatched`.`userId` = :userId
				AND
				`users_seriesWatched`.`seriesWatchId` = :id;

			DELETE
				`seriesWatched`
			FROM
				`seriesWatched`
			LEFT JOIN
				`users_seriesWatched`
			ON
				`users_seriesWatched`.`seriesWatchId` = `seriesWatched`.`id`
			WHERE
				`users_seriesWatched`.`seriesWatchId` IS NULL;
			
		END;

		$arguments = [
			'userId' => $user->id,
			'id'     => $seriesWatch->id
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
