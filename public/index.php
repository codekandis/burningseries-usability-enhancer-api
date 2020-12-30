<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi;

use CodeKandis\SentryClient\SentryClient;
use CodeKandis\Tiphy\Actions\ActionDispatcher;
use CodeKandis\TiphySentryClientIntegration\Throwables\Handlers\InternalServerErrorThrowableHandler;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions\Api\PreDispatchment\AuthenticationPreDispatcher;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Configurations\ConfigurationRegistry;
use function error_reporting;
use function ini_set;
use const E_ALL;

/**
 * Represents the bootstrap script of the project.
 * @package codekandis/burningseries-usability-enhancer-api
 * @author  Christian Ramelow <info@codekandis.net>
 */
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
ini_set( 'html_errors', 'Off' );

require_once __DIR__ . '/../vendor/autoload.php';

/** @var ConfigurationRegistry $configurationRegistry */
$configurationRegistry = ConfigurationRegistry::_();

$sentryClient = new SentryClient( $configurationRegistry->getSentryClientConfiguration() );
$sentryClient->register();

$actionDispatcher = new ActionDispatcher(
	$configurationRegistry->getRoutesConfiguration(),
	new AuthenticationPreDispatcher(),
	new InternalServerErrorThrowableHandler( $sentryClient )
);
$actionDispatcher->dispatch();
