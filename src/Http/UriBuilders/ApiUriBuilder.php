<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders;

use CodeKandis\Tiphy\Http\UriBuilders\AbstractUriBuilder;

class ApiUriBuilder extends AbstractUriBuilder implements ApiUriBuilderInterface
{
	public function buildIndexUri(): string
	{
		return $this->build( 'index' );
	}

	public function buildUsersUri(): string
	{
		return $this->build( 'users' );
	}

	public function buildUserUri( string $userId ): string
	{
		return $this->build( 'user', $userId );
	}

	public function buildUserSeriesDenialsUri( string $userId ): string
	{
		return $this->build( 'userSeriesDenials', $userId );
	}

	public function buildSeriesDenialsUri(): string
	{
		return $this->build( 'seriesDenials' );
	}

	public function buildSeriesDenialUri( string $seriesDenialId ): string
	{
		return $this->build( 'seriesDenial', $seriesDenialId );
	}

	public function buildSeriesDenialUsersUri( string $seriesDenialId ): string
	{
		return $this->build( 'seriesDenialUsers', $seriesDenialId );
	}
}
