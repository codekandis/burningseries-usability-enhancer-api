<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\IndexEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilderInterface;

class IndexApiUriExtender extends AbstractApiUriExtender
{
	/** @var IndexEntity */
	private IndexEntity $index;

	public function __construct( ApiUriBuilderInterface $apiUriBuilder, IndexEntity $index )
	{
		parent::__construct( $apiUriBuilder );
		$this->index = $index;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
		$this->addUsersUri();
		$this->addSeriesDenialsUri();
		$this->addSeriesInterestsUri();
		$this->addSeriesFavoritesUri();
	}

	private function addCanonicalUri(): void
	{
		$this->index->canonicalUri = $this->apiUriBuilder->buildIndexUri();
	}

	private function addUsersUri(): void
	{
		$this->index->usersUri = $this->apiUriBuilder->buildUsersUri();
	}

	private function addSeriesDenialsUri(): void
	{
		$this->index->seriesDenialsUri = $this->apiUriBuilder->buildSeriesDenialsUri();
	}

	private function addSeriesInterestsUri(): void
	{
		$this->index->seriesInterestsUri = $this->apiUriBuilder->buildSeriesInterestsUri();
	}

	private function addSeriesFavoritesUri(): void
	{
		$this->index->seriesFavoritesUri = $this->apiUriBuilder->buildSeriesFavoritesUri();
	}
}
