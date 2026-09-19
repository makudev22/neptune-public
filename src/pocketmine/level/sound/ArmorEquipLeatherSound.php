<?php


declare(strict_types=1);

namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

final class ArmorEquipLeatherSound extends Sound
{
	public function __construct(Vector3 $pos)
	{
		parent::__construct($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
	}

	public function encode()
	{
		return [LevelSoundEventPacket::nonActorSound(LevelSoundEventPacket::SOUND_ARMOR_EQUIP_LEATHER, $this, false)];
	}
}
