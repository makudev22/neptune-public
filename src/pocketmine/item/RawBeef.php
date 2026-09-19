<?php


declare(strict_types=1);

namespace pocketmine\item;

class RawBeef extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::RAW_BEEF, $meta, "Raw Beef");
	}

	public function getFoodRestore() : int
	{
		return 3;
	}

	public function getSaturationRestore() : float
	{
		return 1.8;
	}
}
