<?php


declare(strict_types=1);

namespace pocketmine\level\particle;

use pocketmine\math\Vector3;

class InstantEnchantParticle extends GenericParticle
{
	public function __construct(Vector3 $pos, int $r, int $g, int $b, int $a = 255)
	{
		parent::__construct($pos, Particle::TYPE_MOB_SPELL_INSTANTANEOUS, (($a & 0xff) << 24) | (($r & 0xff) << 16) | (($g & 0xff) << 8) | ($b & 0xff));
	}
}
