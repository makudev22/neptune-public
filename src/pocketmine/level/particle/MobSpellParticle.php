<?php


declare(strict_types=1);

namespace pocketmine\level\particle;

use pocketmine\math\Vector3;

class MobSpellParticle extends GenericParticle
{
	/**
	 * MobSpellParticle constructor.
	 */
	public function __construct(Vector3 $pos, int $r = 0, int $g = 0, int $b = 0, int $a = 255)
	{
		parent::__construct($pos, Particle::TYPE_MOB_SPELL, (($a & 0xff) << 24) | (($r & 0xff) << 16) | (($g & 0xff) << 8) | ($b & 0xff));
	}
}
