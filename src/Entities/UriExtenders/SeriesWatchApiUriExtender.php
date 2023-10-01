<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilderInterface;

class SeriesWatchApiUriExtender extends AbstractApiUriExtender
{
	/** @var SeriesEntity */
	private SeriesEntity $seriesWatch;

	public function __construct( ApiUriBuilderInterface $apiUriBuilder, SeriesEntity $seriesWatch )
	{
		parent::__construct( $apiUriBuilder );
		$this->seriesWatch = $seriesWatch;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
		$this->addSeriesWatchUsersUri();
	}

	private function addCanonicalUri(): void
	{
		$this->seriesWatch->canonicalUri = $this->apiUriBuilder->buildSeriesWatchUri( $this->seriesWatch->id );
	}

	private function addSeriesWatchUsersUri(): void
	{
		$this->seriesWatch->usersUri = $this->apiUriBuilder->buildSeriesWatchUsersUri( $this->seriesWatch->id );
	}
}
