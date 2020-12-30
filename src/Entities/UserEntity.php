<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Entities;

use CodeKandis\Tiphy\Entities\AbstractEntity;

class UserEntity extends AbstractEntity
{
	/** @var string */
	public string $canonicalUri = '';

	/** @var string */
	public string $id = '';

	/** @var string */
	public string $name = '';

	/** @var string */
	public string $email = '';

	/** @var string */
	public string $seriesDenialsUri = '';
}
