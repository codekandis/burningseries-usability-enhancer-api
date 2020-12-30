<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UriExtenders;

use CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities\UserEntity;
use CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders\ApiUriBuilderInterface;

class UserUriExtender extends AbstractUriExtender
{
	/** @var UserEntity */
	private UserEntity $user;

	public function __construct( ApiUriBuilderInterface $uriBuilder, UserEntity $user )
	{
		parent::__construct( $uriBuilder );
		$this->user = $user;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
		$this->addUserSeriesDenialsUri();
	}

	private function addCanonicalUri(): void
	{
		$this->user->canonicalUri = $this->uriBuilder->buildUserUri( $this->user->id );
	}

	private function addUserSeriesDenialsUri(): void
	{
		$this->user->seriesDenialsUri = $this->uriBuilder->buildUserSeriesDenialsUri( $this->user->id );
	}
}
