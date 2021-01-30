<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilderInterface;

class SeriesDenialApiUriExtender extends AbstractApiUriExtender
{
	/** @var SeriesEntity */
	private SeriesEntity $seriesDenial;

	public function __construct( ApiUriBuilderInterface $apiUriBuilder, SeriesEntity $seriesDenial )
	{
		parent::__construct( $apiUriBuilder );
		$this->seriesDenial = $seriesDenial;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
		$this->addSeriesDenialUsersUri();
	}

	private function addCanonicalUri(): void
	{
		$this->seriesDenial->canonicalUri = $this->apiUriBuilder->buildSeriesDenialUri( $this->seriesDenial->id );
	}

	private function addSeriesDenialUsersUri(): void
	{
		$this->seriesDenial->usersUri = $this->apiUriBuilder->buildSeriesDenialUsersUri( $this->seriesDenial->id );
	}
}
