<?php


declare(strict_types=1);

namespace pocketmine\block;

class PoweredRail extends RedstoneRail
{
	protected $id = self::POWERED_RAIL;

	public function getName() : string
	{
		return "Powered Rail";
	}
}
