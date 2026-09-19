<?php


declare(strict_types=1);

namespace pocketmine\block;

class DeepslateIronOre extends IronOre
{
	protected $id = self::DEEPSLATE_IRON_ORE;

	public function getName() : string
	{
		return "Deepslate Iron Ore";
	}

	public function getHardness() : float
	{
		return 4.5;
	}
}
