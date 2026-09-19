<?php


declare(strict_types=1);

namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use function intdiv;
use function min;

final class XpLevelUpSound extends Sound
{
	public function __construct(
		Vector3 $pos,
		private int $xpLevel
	) {
		parent::__construct($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
	}

	public function encode()
	{
		//No idea why such odd numbers, but this works...
		//TODO: check arbitrary volume
		return [LevelSoundEventPacket::nonActorSound(LevelSoundEventPacket::SOUND_LEVELUP, $this, false, 0x10000000 * intdiv(min(30, $this->xpLevel), 5))];
	}
}
