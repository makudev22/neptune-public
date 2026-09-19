<?php


declare(strict_types=1);

namespace pocketmine\item;

class Beetroot extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::BEETROOT, $meta, "Beetroot");
	}

	public function getFoodRestore() : int
	{
		return 1;
	}

	public function getSaturationRestore() : float
	{
		return 1.2;
	}
}
