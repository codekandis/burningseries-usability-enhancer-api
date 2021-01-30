<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions;

use CodeKandis\Tiphy\Actions\AbstractAction;
use CodeKandis\Tiphy\Persistence\MariaDb\Connector;
use CodeKandis\Tiphy\Persistence\MariaDb\ConnectorInterface;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Configurations\ConfigurationRegistry;

abstract class AbstractWithDatabaseConnectorAction extends AbstractAction
{
	/** @var ConnectorInterface */
	private ConnectorInterface $databaseConnector;

	protected function getDatabaseConnector(): ConnectorInterface
	{
		return $this->databaseConnector ??
			   $this->databaseConnector = new Connector(
				   ConfigurationRegistry::_()->getPersistenceConfiguration()
			   );
	}
}
