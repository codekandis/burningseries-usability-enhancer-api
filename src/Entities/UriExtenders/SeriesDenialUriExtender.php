<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesDenialEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilderInterface;

class SeriesDenialUriExtender extends AbstractUriExtender
{
	/** @var SeriesDenialEntity */
	private SeriesDenialEntity $seriesDenial;

	public function __construct( ApiUriBuilderInterface $uriBuilder, SeriesDenialEntity $seriesDenial )
	{
		parent::__construct( $uriBuilder );
		$this->seriesDenial = $seriesDenial;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
		$this->addSeriesDenialUsersUri();
	}

	private function addCanonicalUri(): void
	{
		$this->seriesDenial->canonicalUri = $this->uriBuilder->buildSeriesDenialUri( $this->seriesDenial->id );
	}

	private function addSeriesDenialUsersUri(): void
	{
		$this->seriesDenial->usersUri = $this->uriBuilder->buildSeriesDenialUsersUri( $this->seriesDenial->id );
	}
}
