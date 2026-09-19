<?php


declare(strict_types=1);

namespace pocketmine\item;

class Clock extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::CLOCK, $meta, "Clock");
	}
}
