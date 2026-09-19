<?php


declare(strict_types=1);

namespace pocketmine\block;

class DeepslateLapisOre extends LapisOre
{
	protected $id = self::DEEPSLATE_LAPIS_ORE;

	public function getHardness() : float
	{
		return 4.5;
	}

	public function getName() : string
	{
		return "Deepslate Lapis Ore";
	}
}
