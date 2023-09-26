<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\Tiphy\Persistence\MariaDb\PreparedStatementInArrayHelper;
use CodeKandis\Tiphy\Persistence\MariaDb\Repositories\AbstractRepository;
use CodeKandis\Tiphy\Persistence\PersistenceException;

class SeriesFavoritesRepository extends AbstractRepository
{
	/**
	 * @return SeriesEntity[]
	 * @throws PersistenceException
	 */
	public function readSeriesFavorites(): array
	{
		$query = <<< END
			SELECT
				`seriesFavorites`.*
			FROM
				`seriesFavorites`
			ORDER BY
				`seriesFavorites`.`createdOn` DESC;
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
	public function readSeriesFavoriteById( SeriesEntity $seriesFavorite ): ?SeriesEntity
	{
		$query = <<< END
			SELECT
				`seriesFavorites`.*
			FROM
				`seriesFavorites`
			WHERE
				`seriesFavorites`.`id` = :id
			LIMIT
				0, 1;
		END;

		$arguments = [
			'id' => $seriesFavorite->id
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
	public function readSeriesFavoritesByUserId( UserEntity $user ): array
	{
		$query = <<< END
			SELECT
				`seriesFavorites`.*
			FROM
				`seriesFavorites`
			INNER JOIN
				`users_seriesFavorites`
				ON
				`users_seriesFavorites`.`userId` = :userId
			WHERE
				`seriesFavorites`.`id` = `users_seriesFavorites`.`seriesFavoriteId`
			ORDER BY
				`seriesFavorites`.`createdOn` DESC;
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
	public function readSeriesFavoritesFilteredByNamesAndUserId( array $series, UserEntity $user ): array
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
				`seriesFavorites`.*
			FROM
				`seriesFavorites`
			INNER JOIN
				`users_seriesFavorites`
				ON
				`users_seriesFavorites`.`userId` = :userId
			WHERE
			    `seriesFavorites`.name IN ( {$inArrayHelper->getNamedPlaceholders()} )
			    AND
				`seriesFavorites`.`id` = `users_seriesFavorites`.`seriesFavoriteId`
			ORDER BY
				`seriesFavorites`.`createdOn` DESC;
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
	public function writeSeriesFavoriteByNameAndUserId( SeriesEntity $seriesFavoriteEntity, UserEntity $user ): void
	{
		$query = <<< END
			INSERT INTO
				`seriesFavorites`
				( `id`, `name`, `uri`, `createdOn` )
			VALUES
				( UUID( ), LOWER( :name ), :uri, :createdOn )
			ON DUPLICATE KEY UPDATE
				`createdOn` = IF ( `createdOn` IS NULL OR `createdOn` > :createdOn, :createdOn, `createdOn` );

			INSERT IGNORE INTO
				`users_seriesFavorites`
				( `id`, `userId`, `seriesFavoriteId`)
			SELECT
				UUID( ),
				:userId,
				`seriesFavorites`.`id`
			FROM
				`seriesFavorites`
			WHERE
				`seriesFavorites`.`name` = :name;
		END;

		$arguments = [
			'userId'    => $user->id,
			'name'      => $seriesFavoriteEntity->name,
			'uri'       => $seriesFavoriteEntity->uri,
			'createdOn' => $seriesFavoriteEntity->createdOn
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
	public function deleteSeriesFavoriteByIdAndUserId( SeriesEntity $seriesFavorite, UserEntity $user ): void
	{
		$query = <<< END
			DELETE
			FROM
				`users_seriesFavorites`
			WHERE
				`users_seriesFavorites`.`userId` = :userId
				AND
				`users_seriesFavorites`.`seriesFavoriteId` = :id;

			DELETE
				`seriesFavorites`
			FROM
				`seriesFavorites`
			LEFT JOIN
				`users_seriesFavorites`
			ON
				`users_seriesFavorites`.`seriesFavoriteId` = `seriesFavorites`.`id`
			WHERE
				`users_seriesFavorites`.`seriesFavoriteId` IS NULL;
			
		END;

		$arguments = [
			'userId' => $user->id,
			'id'     => $seriesFavorite->id
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
