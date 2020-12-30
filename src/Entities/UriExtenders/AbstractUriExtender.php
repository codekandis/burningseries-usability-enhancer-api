<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilderInterface;
use CodeKandis\Tiphy\Entities\UriExtenders\UriExtenderInterface;

abstract class AbstractUriExtender implements UriExtenderInterface
{
	/** @var ApiUriBuilderInterface */
	protected ApiUriBuilderInterface $uriBuilder;

	public function __construct( ApiUriBuilderInterface $uriBuilder )
	{
		$this->uriBuilder = $uriBuilder;
	}
}
