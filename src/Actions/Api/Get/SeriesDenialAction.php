<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\Get;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Configurations\ConfigurationRegistry;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesDenialEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders\SeriesDenialUriExtender;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesDenialsErrorCodes;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors\SeriesDenialsErrorMessages;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilder;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilderInterface;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Persistence\MariaDb\Repositories\SeriesDenialsRepository;
use CodeKandis\Tiphy\Actions\AbstractAction;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\MariaDb\Connector;
use CodeKandis\Tiphy\Persistence\MariaDb\ConnectorInterface;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class SeriesDenialAction extends AbstractAction
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
		$inputData = $this->getInputData();

		$requestedSeriesDenial     = new SeriesDenialEntity();
		$requestedSeriesDenial->id = $inputData[ 'seriesDenialId' ];
		$seriesDenial              = $this->readSeriesDenialById( $requestedSeriesDenial );

		if ( null === $seriesDenial )
		{
			$errorInformation = new ErrorInformation( SeriesDenialsErrorCodes::SERIES_UNKNOWN, SeriesDenialsErrorMessages::SERIES_UNKNOWN, $inputData );
			( new JsonResponder( StatusCodes::NOT_FOUND, null, $errorInformation ) )
				->respond();

			return;
		}

		$this->extendUris( $seriesDenial );

		$responderData = [
			'seriesDenial' => $seriesDenial
		];
		( new JsonResponder( StatusCodes::OK, $responderData ) )
			->respond();
	}

	/**
	 * @return string[]
	 */
	private function getInputData(): array
	{
		return $this->arguments;
	}

	private function extendUris( SeriesDenialEntity $seriesDenial ): void
	{
		$uriBuilder = $this->getUriBuilder();
		( new SeriesDenialUriExtender( $uriBuilder, $seriesDenial ) )
			->extend();
	}

	/**
	 * @throws PersistenceException
	 */
	private function readSeriesDenialById( SeriesDenialEntity $requestedSeriesDenial ): ?SeriesDenialEntity
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new SeriesDenialsRepository( $databaseConnector ) )
			->readSeriesDenialById( $requestedSeriesDenial );
	}
}
