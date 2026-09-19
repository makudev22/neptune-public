<?php


declare(strict_types=1);

namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

final class DoorBumpSound extends GenericSound
{
	public function __construct(Vector3 $pos)
	{
		parent::__construct($pos, LevelEventPacket::EVENT_SOUND_DOOR_BUMP);
	}
}
