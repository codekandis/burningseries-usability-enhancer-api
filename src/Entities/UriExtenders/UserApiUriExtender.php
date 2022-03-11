<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilderInterface;

class UserApiUriExtender extends AbstractApiUriExtender
{
	/** @var UserEntity */
	private UserEntity $user;

	public function __construct( ApiUriBuilderInterface $apiUriBuilder, UserEntity $user )
	{
		parent::__construct( $apiUriBuilder );
		$this->user = $user;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
		$this->addUserSeriesDenialsUri();
		$this->addUserSeriesInterestsUri();
		$this->addUserSeriesFavoritesUri();
	}

	private function addCanonicalUri(): void
	{
		$this->user->canonicalUri = $this->apiUriBuilder->buildUserUri( $this->user->id );
	}

	private function addUserSeriesDenialsUri(): void
	{
		$this->user->seriesDenialsUri = $this->apiUriBuilder->buildUserSeriesDenialsUri( $this->user->id );
	}

	private function addUserSeriesInterestsUri(): void
	{
		$this->user->seriesInterestsUri = $this->apiUriBuilder->buildUserSeriesInterestsUri( $this->user->id );
	}

	private function addUserSeriesFavoritesUri(): void
	{
		$this->user->seriesFavoritesUri = $this->apiUriBuilder->buildUserSeriesFavoritesUri( $this->user->id );
	}
}
