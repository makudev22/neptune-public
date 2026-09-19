<?php


declare(strict_types=1);

namespace pocketmine\level\particle;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

class MobSpawnParticle extends Particle
{
	protected int $width;
	protected int $height;

	public function __construct(Vector3 $pos, int $width = 0, int $height = 0)
	{
		parent::__construct($pos->x, $pos->y, $pos->z);
		$this->width = $width;
		$this->height = $height;
	}

	public function encode()
	{
		$pk = new LevelEventPacket();
		$pk->evid = LevelEventPacket::EVENT_PARTICLE_SPAWN;
		$pk->position = $this->asVector3();
		$pk->data = ($this->width & 0xff) + (($this->height & 0xff) << 8);

		return $pk;
	}
}
