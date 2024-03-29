<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities;

use CodeKandis\Tiphy\Entities\AbstractEntity;

class IndexEntity extends AbstractEntity
{
	/** @var string */
	public string $canonicalUri = '';

	/** @var string */
	public string $usersUri = '';

	/** @var string */
	public string $seriesDenialsUri = '';

	/** @var string */
	public string $seriesInterestsUri = '';

	/** @var string */
	public string $seriesFavoritesUri = '';

	/** @var string */
	public string $seriesWatchedUri = '';
}
