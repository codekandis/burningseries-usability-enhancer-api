<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\SeriesEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilderInterface;

class SeriesFavoriteApiUriExtender extends AbstractApiUriExtender
{
	/** @var SeriesEntity */
	private SeriesEntity $seriesFavorite;

	public function __construct( ApiUriBuilderInterface $apiUriBuilder, SeriesEntity $seriesFavorite )
	{
		parent::__construct( $apiUriBuilder );
		$this->seriesFavorite = $seriesFavorite;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
		$this->addSeriesFavoriteUsersUri();
	}

	private function addCanonicalUri(): void
	{
		$this->seriesFavorite->canonicalUri = $this->apiUriBuilder->buildSeriesFavoriteUri( $this->seriesFavorite->id );
	}

	private function addSeriesFavoriteUsersUri(): void
	{
		$this->seriesFavorite->usersUri = $this->apiUriBuilder->buildSeriesFavoriteUsersUri( $this->seriesFavorite->id );
	}
}
