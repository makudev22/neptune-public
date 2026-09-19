<?php


declare(strict_types=1);

namespace pocketmine\item;

class MushroomStew extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::MUSHROOM_STEW, $meta, "Mushroom Stew");
	}

	public function getMaxStackSize() : int
	{
		return 1;
	}

	public function getFoodRestore() : int
	{
		return 6;
	}

	public function getSaturationRestore() : float
	{
		return 7.2;
	}

	public function getResidue()
	{
		return ItemFactory::get(Item::BOWL);
	}
}
