<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilderInterface;

class SeriesInterestApiUriExtender extends AbstractApiUriExtender
{
	/** @var SeriesEntity */
	private SeriesEntity $seriesInterest;

	public function __construct( ApiUriBuilderInterface $apiUriBuilder, SeriesEntity $seriesInterest )
	{
		parent::__construct( $apiUriBuilder );
		$this->seriesInterest = $seriesInterest;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
		$this->addSeriesInterestUsersUri();
	}

	private function addCanonicalUri(): void
	{
		$this->seriesInterest->canonicalUri = $this->apiUriBuilder->buildSeriesInterestUri( $this->seriesInterest->id );
	}

	private function addSeriesInterestUsersUri(): void
	{
		$this->seriesInterest->usersUri = $this->apiUriBuilder->buildSeriesInterestUsersUri( $this->seriesInterest->id );
	}
}
