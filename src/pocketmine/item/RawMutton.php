<?php


declare(strict_types=1);

namespace pocketmine\item;

class RawMutton extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::RAW_MUTTON, $meta, "Raw Mutton");
	}

	public function getFoodRestore() : int
	{
		return 2;
	}

	public function getSaturationRestore() : float
	{
		return 1.2;
	}
}
