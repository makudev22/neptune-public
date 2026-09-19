<?php


declare(strict_types=1);

namespace pocketmine\item;

class BlazeRod extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::BLAZE_ROD, $meta, "Blaze Rod");
	}

	public function getFuelTime() : int
	{
		return 2400;
	}
}
