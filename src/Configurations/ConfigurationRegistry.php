<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Configurations;

use CodeKandis\TiphySentryClientIntegration\Configurations\AbstractConfigurationRegistry;
use function dirname;

class ConfigurationRegistry extends AbstractConfigurationRegistry
{
	protected function initialize(): void
	{
		$this->setSentryClientConfigurationPath( dirname( __DIR__, 2 ) . '/config/sentryClient.php' );
		$this->setRoutesConfigurationPath( dirname( __DIR__, 2 ) . '/config/routes.php' );
		$this->setPersistenceConfigurationPath( dirname( __DIR__, 2 ) . '/config/persistence.php' );
		$this->setUriBuilderConfigurationPath( dirname( __DIR__, 2 ) . '/config/uriBuilder.php' );
	}
}
