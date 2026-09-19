<?php


declare(strict_types=1);

namespace pocketmine\block;

class DeepslateCoalOre extends CoalOre
{
	protected $id = self::DEEPSLATE_COAL_ORE;

	public function getName() : string
	{
		return "Deepslate Coal Ore";
	}

	public function getHardness() : float
	{
		return 4.5;
	}
}
