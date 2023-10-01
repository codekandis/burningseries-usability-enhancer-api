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

	public function buildUserSeriesInterestsUri( string $userId ): string
	{
		return $this->build( 'userSeriesInterests', $userId );
	}

	public function buildSeriesInterestsUri(): string
	{
		return $this->build( 'seriesInterests' );
	}

	public function buildSeriesInterestUri( string $seriesInterestId ): string
	{
		return $this->build( 'seriesInterest', $seriesInterestId );
	}

	public function buildSeriesInterestUsersUri( string $seriesInterestId ): string
	{
		return $this->build( 'seriesInterestUsers', $seriesInterestId );
	}

	public function buildUserSeriesFavoritesUri( string $userId ): string
	{
		return $this->build( 'userSeriesFavorites', $userId );
	}

	public function buildSeriesFavoritesUri(): string
	{
		return $this->build( 'seriesFavorites' );
	}

	public function buildSeriesFavoriteUri( string $seriesFavoriteId ): string
	{
		return $this->build( 'seriesFavorite', $seriesFavoriteId );
	}

	public function buildSeriesFavoriteUsersUri( string $seriesFavoriteId ): string
	{
		return $this->build( 'seriesFavoriteUsers', $seriesFavoriteId );
	}

	public function buildUserSeriesWatchedUri( string $userId ): string
	{
		return $this->build( 'userSeriesWatched', $userId );
	}

	public function buildSeriesWatchedUri(): string
	{
		return $this->build( 'seriesWatched' );
	}

	public function buildSeriesWatchUri( string $seriesWatchId ): string
	{
		return $this->build( 'seriesWatch', $seriesWatchId );
	}

	public function buildSeriesWatchUsersUri( string $seriesWatchId ): string
	{
		return $this->build( 'seriesWatchUsers', $seriesWatchId );
	}
}
