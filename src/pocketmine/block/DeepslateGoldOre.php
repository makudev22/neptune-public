<?php


declare(strict_types=1);

namespace pocketmine\block;

class DeepslateGoldOre extends GoldOre
{
	protected $id = self::DEEPSLATE_GOLD_ORE;

	public function getName() : string
	{
		return "Deepslate Gold Ore";
	}

	public function getHardness() : float
	{
		return 4.5;
	}
}
