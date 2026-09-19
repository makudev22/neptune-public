<?php


declare(strict_types=1);

namespace pocketmine\block;

class SoulLantern extends Lantern
{
	protected $id = self::SOUL_LANTERN;

	public function getName() : string
	{
		return "Soul Lantern";
	}

	public function getLightLevel() : int
	{
		return 10;
	}
}
