<?php


declare(strict_types=1);

namespace pocketmine\block;

class WallSign extends SignPost
{
	public function onNearbyBlockChange() : void
	{
		if ($this->getSide($this->meta ^ 0x01)->getId() === self::AIR) {
			$this->getLevel()->useBreakOn($this);
		}
	}
}
