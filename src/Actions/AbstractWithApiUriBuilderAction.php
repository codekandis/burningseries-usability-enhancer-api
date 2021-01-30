<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Actions;

use CodeKandis\Tiphy\Actions\AbstractAction;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Configurations\ConfigurationRegistry;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilder;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilderInterface;

abstract class AbstractWithApiUriBuilderAction extends AbstractAction
{
	/** @var ApiUriBuilderInterface */
	private ApiUriBuilderInterface $apiUriBuilder;

	protected function getApiUriBuilder(): ApiUriBuilderInterface
	{
		return $this->apiUriBuilder ??
			   $this->apiUriBuilder = new ApiUriBuilder(
				   ConfigurationRegistry::_()->getUriBuilderConfiguration()
			   );
	}
}
