<?php


declare(strict_types=1);

namespace pocketmine\item;

class Stick extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::STICK, $meta, "Stick");
	}

	public function getFuelTime() : int
	{
		return 100;
	}
}
