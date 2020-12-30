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
}
