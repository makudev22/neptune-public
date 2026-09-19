<?php


declare(strict_types=1);

namespace pocketmine\item;

class Melon extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::MELON, $meta, "Melon");
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
