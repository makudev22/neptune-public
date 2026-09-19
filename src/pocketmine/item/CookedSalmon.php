<?php


declare(strict_types=1);

namespace pocketmine\item;

class CookedSalmon extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::COOKED_SALMON, $meta, "Cooked Salmon");
	}

	public function getFoodRestore() : int
	{
		return 6;
	}

	public function getSaturationRestore() : float
	{
		return 9.6;
	}
}
