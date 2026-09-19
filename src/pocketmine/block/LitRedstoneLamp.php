<?php


declare(strict_types=1);

namespace pocketmine\block;

class LitRedstoneLamp extends RedstoneLamp
{
	protected $id = self::LIT_REDSTONE_LAMP;

	public function getName() : string
	{
		return "Lit Redstone Lamp";
	}

	public function getLightLevel() : int
	{
		return 15;
	}
}
