<?php


declare(strict_types=1);

namespace pocketmine\item;

class Totem extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::TOTEM, $meta, "Totem of Undying");
	}

	public function getMaxStackSize() : int
	{
		return 1;
	}
}
