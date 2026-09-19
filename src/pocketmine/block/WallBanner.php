<?php


declare(strict_types=1);

namespace pocketmine\block;

class WallBanner extends StandingBanner
{
	protected $id = self::WALL_BANNER;

	public function getName() : string
	{
		return "Wall Banner";
	}

	public function onNearbyBlockChange() : void
	{
		if ($this->getSide($this->meta ^ 0x01)->getId() === self::AIR) {
			$this->getLevel()->useBreakOn($this);
		}
	}
}
