<?php


declare(strict_types=1);

namespace pocketmine\item;

class RabbitStew extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::RABBIT_STEW, $meta, "Rabbit Stew");
	}

	public function getMaxStackSize() : int
	{
		return 1;
	}

	public function getFoodRestore() : int
	{
		return 10;
	}

	public function getSaturationRestore() : float
	{
		return 12;
	}

	public function getResidue()
	{
		return ItemFactory::get(Item::BOWL);
	}
}
