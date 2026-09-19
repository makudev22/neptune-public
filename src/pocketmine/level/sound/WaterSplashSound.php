<?php


declare(strict_types=1);

namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

final class WaterSplashSound extends Sound
{
	public function __construct(
		Vector3 $pos,
		private float $volume
	) {
		parent::__construct($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
		if ($volume < 0 || $volume > 1) {
			throw new \InvalidArgumentException("Volume must be between 0 and 1");
		}
	}

	public function encode()
	{
		return [LevelSoundEventPacket::create(
			LevelSoundEventPacket::SOUND_SPLASH,
			$this,
			(int) ($this->volume * 16777215),
			":",
			false,
			false,
			-1,
			null
		)];
	}
}
