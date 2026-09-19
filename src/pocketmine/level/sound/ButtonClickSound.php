<?php


declare(strict_types=1);

namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

final class ButtonClickSound extends GenericSound
{
	public function __construct(Vector3 $pos, float $pitch = 0)
	{
		parent::__construct($pos, LevelEventPacket::EVENT_REDSTONE_TRIGGER, $pitch);
	}
}
