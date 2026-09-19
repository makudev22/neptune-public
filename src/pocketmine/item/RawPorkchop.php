<?php


declare(strict_types=1);

namespace pocketmine\item;

class RawPorkchop extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::RAW_PORKCHOP, $meta, "Raw Porkchop");
	}

	public function getFoodRestore() : int
	{
		return 3;
	}

	public function getSaturationRestore() : float
	{
		return 0.6;
	}
}
