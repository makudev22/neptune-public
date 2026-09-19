<?php


declare(strict_types=1);

namespace pocketmine\item;

class RawFish extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::RAW_FISH, $meta, "Raw Fish");
	}

	public function getFoodRestore() : int
	{
		return 2;
	}

	public function getSaturationRestore() : float
	{
		return 0.4;
	}
}
