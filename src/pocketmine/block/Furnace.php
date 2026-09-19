<?php


declare(strict_types=1);

namespace pocketmine\block;

class Furnace extends BurningFurnace
{
	protected $id = self::FURNACE;

	public function getName() : string
	{
		return "Furnace";
	}

	public function getLightLevel() : int
	{
		return 0;
	}
}
