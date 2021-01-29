<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Configurations;

use CodeKandis\TiphySentryClientIntegration\Configurations\AbstractConfigurationRegistry;
use function dirname;

class ConfigurationRegistry extends AbstractConfigurationRegistry
{
	protected function initialize(): void
	{
		$this->initializeSentryClientConfiguration();
		$this->initializeRoutesConfiguration();
		$this->initializePersistenceConfiguration();
		$this->initializeUriBuilderConfiguration();
	}

	private function initializeSentryClientConfiguration(): void
	{
		$this->setPlainSentryClientConfiguration(
			require dirname( __DIR__, 2 ) . '/config/sentryClient.php'
		);
	}

	private function initializeRoutesConfiguration(): void
	{
		$this->setPlainRoutesConfiguration(
			require __DIR__ . '/Plain/routes.php'
		);
	}

	private function initializePersistenceConfiguration(): void
	{
		$this->setPlainPersistenceConfiguration(
			require dirname( __DIR__, 2 ) . '/config/persistence.php'
		);
	}

	private function initializeUriBuilderConfiguration(): void
	{
		$this->setPlainUriBuilderConfiguration(
			( require dirname( __DIR__, 2 ) . '/config/uriBuilder.php' )
			+ ( require __DIR__ . '/Plain/uriBuilder.php' )
		);
	}
}
