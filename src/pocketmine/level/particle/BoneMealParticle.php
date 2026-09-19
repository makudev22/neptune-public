<?php


declare(strict_types=1);

namespace pocketmine\level\particle;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

class BoneMealParticle extends Particle
{
	public function __construct(Vector3 $pos)
	{
		parent::__construct($pos->x, $pos->y, $pos->z);
	}

	public function encode()
	{
		$pk = new LevelEventPacket();
		$pk->evid = LevelEventPacket::EVENT_BONE_MEAL_USE;
		$pk->position = $this->asVector3();
		$pk->data = 0;
		return $pk;
	}
}
