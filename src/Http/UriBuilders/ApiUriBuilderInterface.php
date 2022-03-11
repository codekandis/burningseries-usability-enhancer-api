<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Http\UriBuilders;

interface ApiUriBuilderInterface
{
	public function buildIndexUri(): string;

	public function buildUsersUri(): string;

	public function buildUserUri( string $userId ): string;

	public function buildUserSeriesDenialsUri( string $userId ): string;

	public function buildSeriesDenialsUri(): string;

	public function buildSeriesDenialUri( string $seriesDenialId ): string;

	public function buildSeriesDenialUsersUri( string $seriesDenialId ): string;

	public function buildUserSeriesInterestsUri( string $userId ): string;

	public function buildSeriesInterestsUri(): string;

	public function buildSeriesInterestUri( string $seriesInterestId ): string;

	public function buildSeriesInterestUsersUri( string $seriesInterestId ): string;

	public function buildUserSeriesFavoritesUri( string $userId ): string;

	public function buildSeriesFavoritesUri(): string;

	public function buildSeriesFavoriteUri( string $seriesFavoriteId ): string;

	public function buildSeriesFavoriteUsersUri( string $seriesFavoriteId ): string;
}
