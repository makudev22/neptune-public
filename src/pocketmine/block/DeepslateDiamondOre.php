<?php


declare(strict_types=1);

namespace pocketmine\block;

class DeepslateDiamondOre extends DiamondOre
{
	protected $id = self::DEEPSLATE_DIAMOND_ORE;

	public function getName() : string
	{
		return "Deepslate Diamond Ore";
	}

	public function getHardness() : float
	{
		return 4.5;
	}
}
