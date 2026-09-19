<?php


declare(strict_types=1);

namespace pocketmine\item;

class Apple extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::APPLE, $meta, "Apple");
	}

	public function getFoodRestore() : int
	{
		return 4;
	}

	public function getSaturationRestore() : float
	{
		return 2.4;
	}
}
