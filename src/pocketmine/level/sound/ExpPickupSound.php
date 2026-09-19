<?php


declare(strict_types=1);

namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

final class ExpPickupSound extends GenericSound
{
	public function __construct(Vector3 $pos, $pitch = 0)
	{
		parent::__construct($pos, LevelEventPacket::EVENT_SOUND_ORB, $pitch);
	}
}
