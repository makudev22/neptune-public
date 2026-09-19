<?php


declare(strict_types=1);

namespace pocketmine\block;

class LitPumpkin extends Pumpkin
{
	protected $id = self::LIT_PUMPKIN;

	public function getLightLevel() : int
	{
		return 15;
	}

	public function getName() : string
	{
		return "Jack o'Lantern";
	}
}
