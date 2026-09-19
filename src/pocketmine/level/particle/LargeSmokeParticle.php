<?php


declare(strict_types=1);

namespace pocketmine\level\particle;

use pocketmine\math\Vector3;

class LargeSmokeParticle extends GenericParticle
{
	public function __construct(Vector3 $pos, int $scale = 0)
	{
		parent::__construct($pos, Particle::TYPE_LARGE_SMOKE, $scale);
	}
}
