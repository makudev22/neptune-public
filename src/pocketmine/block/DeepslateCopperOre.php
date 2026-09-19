<?php


declare(strict_types=1);

namespace pocketmine\block;

class DeepslateCopperOre extends CopperOre
{
	protected $id = self::DEEPSLATE_COPPER_ORE;

	public function getName() : string
	{
		return "Deepslate Copper Ore";
	}

	public function getHardness() : float
	{
		return 4.5;
	}
}
