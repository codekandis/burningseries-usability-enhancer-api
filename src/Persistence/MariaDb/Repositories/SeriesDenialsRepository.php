<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\Tiphy\Persistence\MariaDb\PreparedStatementInArrayHelper;
use CodeKandis\Tiphy\Persistence\MariaDb\Repositories\AbstractRepository;
use CodeKandis\Tiphy\Persistence\PersistenceException;

class SeriesDenialsRepository extends AbstractRepository
{
	/**
	 * @return SeriesEntity[]
	 * @throws PersistenceException
	 */
	public function readSeriesDenials(): array
	{
		$query = <<< END
			SELECT
				`seriesDenials`.*
			FROM
				`seriesDenials`
			ORDER BY
				`seriesDenials`.`createdOn` DESC;
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
	public function readSeriesDenialById( SeriesEntity $seriesDenial ): ?SeriesEntity
	{
		$query = <<< END
			SELECT
				`seriesDenials`.*
			FROM
				`seriesDenials`
			WHERE
				`seriesDenials`.`id` = :id
			LIMIT
				0, 1;
		END;

		$arguments = [
			'id' => $seriesDenial->id
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
	public function readSeriesDenialByNameAndUserId( SeriesEntity $seriesDenial, UserEntity $user ): ?SeriesEntity
	{
		$query = <<< END
			SELECT
				`seriesDenials`.*
			FROM
				`seriesDenials`
			INNER JOIN
				`users_seriesDenials`
				ON
				`users_seriesDenials`.`userId` = :userId
			WHERE
			    `seriesDenials`.name = :name
			    AND
				`seriesDenials`.`id` = `users_seriesDenials`.`seriesDenialId`
			LIMIT
				0, 1;
		END;

		$arguments = [
			'name'   => $seriesDenial->name,
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
	public function readSeriesDenialsByUserId( UserEntity $user ): array
	{
		$query = <<< END
			SELECT
				`seriesDenials`.*
			FROM
				`seriesDenials`
			INNER JOIN
				`users_seriesDenials`
				ON
				`users_seriesDenials`.`userId` = :userId
			WHERE
				`seriesDenials`.`id` = `users_seriesDenials`.`seriesDenialId`
			ORDER BY
				`seriesDenials`.`createdOn` DESC;
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
	public function readSeriesDenialsFilteredByNamesAndUserId( array $series, UserEntity $user ): array
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
				`seriesDenials`.*
			FROM
				`seriesDenials`
			INNER JOIN
				`users_seriesDenials`
				ON
				`users_seriesDenials`.`userId` = :userId
			WHERE
			    `seriesDenials`.name IN ( {$inArrayHelper->getNamedPlaceholders()} )
			    AND
				`seriesDenials`.`id` = `users_seriesDenials`.`seriesDenialId`
			ORDER BY
				`seriesDenials`.`createdOn` DESC;
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
	public function writeSeriesDenialByNameAndUserId( SeriesEntity $seriesDenialEntity, UserEntity $user ): void
	{
		$query = <<< END
			INSERT INTO
				`seriesDenials`
				( `id`, `name`, `uri`, `createdOn` )
			VALUES
				( UUID( ), LOWER( :name ), :uri, :createdOn )
			ON DUPLICATE KEY UPDATE
				`createdOn` = IF ( `createdOn` IS NULL OR `createdOn` > :createdOn, :createdOn, `createdOn` );

			INSERT IGNORE INTO
				`users_seriesDenials`
				( `id`, `userId`, `seriesDenialId`)
			SELECT
				UUID( ),
				:userId,
				`seriesDenials`.`id`
			FROM
				`seriesDenials`
			WHERE
				`seriesDenials`.`name` = :name;
		END;

		$arguments = [
			'userId'    => $user->id,
			'name'      => $seriesDenialEntity->name,
			'uri'       => $seriesDenialEntity->uri,
			'createdOn' => $seriesDenialEntity->createdOn
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
	public function deleteSeriesDenialByIdAndUserId( SeriesEntity $seriesDenial, UserEntity $user ): void
	{
		$query = <<< END
			DELETE
			FROM
				`users_seriesDenials`
			WHERE
				`users_seriesDenials`.`userId` = :userId
				AND
				`users_seriesDenials`.`seriesDenialId` = :id;

			DELETE
				`seriesDenials`
			FROM
				`seriesDenials`
			LEFT JOIN
				`users_seriesDenials`
			ON
				`users_seriesDenials`.`seriesDenialId` = `seriesDenials`.`id`
			WHERE
				`users_seriesDenials`.`seriesDenialId` IS NULL;
			
		END;

		$arguments = [
			'userId' => $user->id,
			'id'     => $seriesDenial->id
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
