<?php


declare(strict_types=1);

namespace pocketmine\block;

class DeepslateEmeraldOre extends CoalOre
{
	protected $id = self::DEEPSLATE_EMERALD_ORE;

	public function getName() : string
	{
		return "Deepslate Emerald Ore";
	}

	public function getHardness() : float
	{
		return 4.5;
	}
}
