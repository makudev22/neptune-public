<?php


declare(strict_types=1);

namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

final class ItemFrameAddItemSound extends GenericSound
{
	public function __construct(Vector3 $pos)
	{
		parent::__construct($pos, LevelEventPacket::EVENT_SOUND_ITEMFRAME_ADD_ITEM);
	}
}
