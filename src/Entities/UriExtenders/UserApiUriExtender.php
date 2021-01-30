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
	}

	private function addCanonicalUri(): void
	{
		$this->user->canonicalUri = $this->apiUriBuilder->buildUserUri( $this->user->id );
	}

	private function addUserSeriesDenialsUri(): void
	{
		$this->user->seriesDenialsUri = $this->apiUriBuilder->buildUserSeriesDenialsUri( $this->user->id );
	}
}
