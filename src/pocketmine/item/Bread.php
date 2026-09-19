<?php


declare(strict_types=1);

namespace pocketmine\item;

class Bread extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::BREAD, $meta, "Bread");
	}

	public function getFoodRestore() : int
	{
		return 5;
	}

	public function getSaturationRestore() : float
	{
		return 6;
	}
}
