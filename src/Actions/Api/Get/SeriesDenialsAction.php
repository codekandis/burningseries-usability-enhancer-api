<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Configurations\ConfigurationRegistry;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\SeriesDenialUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilder;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilderInterface;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesDenialsRepository;
use CodeKandis\Tiphy\Actions\AbstractAction;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\MariaDb\Connector;
use CodeKandis\Tiphy\Persistence\MariaDb\ConnectorInterface;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use JsonException;

class SeriesDenialsAction extends AbstractAction
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
		$seriesDenials = $this->readSeriesDenials();
		$this->extendUris( $seriesDenials );

		$responderData = [
			'seriesDenials' => $seriesDenials,
		];
		( new JsonResponder( StatusCodes::OK, $responderData ) )
			->respond();
	}

	/**
	 * @param SeriesEntity[] $seriesDenials
	 */
	private function extendUris( array $seriesDenials ): void
	{
		$uriBuilder = $this->getUriBuilder();
		foreach ( $seriesDenials as $seriesDenial )
		{
			( new SeriesDenialUriExtender( $uriBuilder, $seriesDenial ) )
				->extend();
		}
	}

	/**
	 * @return SeriesEntity[]
	 * @throws PersistenceException
	 */
	private function readSeriesDenials(): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new SeriesDenialsRepository( $databaseConnector ) )
			->readSeriesDenials();
	}
}
