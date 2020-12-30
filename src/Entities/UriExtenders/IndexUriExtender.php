<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\IndexEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilderInterface;

class IndexUriExtender extends AbstractUriExtender
{
	/** @var IndexEntity */
	private IndexEntity $index;

	public function __construct( ApiUriBuilderInterface $uriBuilder, IndexEntity $index )
	{
		parent::__construct( $uriBuilder );
		$this->index = $index;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
		$this->addUsersUri();
		$this->addSeriesDenialsUri();
	}

	private function addCanonicalUri(): void
	{
		$this->index->canonicalUri = $this->uriBuilder->buildIndexUri();
	}

	private function addUsersUri(): void
	{
		$this->index->usersUri = $this->uriBuilder->buildUsersUri();
	}

	private function addSeriesDenialsUri(): void
	{
		$this->index->seriesDenialsUri = $this->uriBuilder->buildSeriesDenialsUri();
	}
}
