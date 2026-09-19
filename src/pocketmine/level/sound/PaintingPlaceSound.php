<?php


declare(strict_types=1);

namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

final class PaintingPlaceSound extends GenericSound
{
	public function __construct(Vector3 $pos, float $pitch = 0)
	{
		//item frame and painting have the same sound
		parent::__construct($pos, LevelEventPacket::EVENT_SOUND_ITEMFRAME_PLACE, $pitch);
	}
}
