<?php


declare(strict_types=1);

namespace pocketmine\item;

class Compass extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::COMPASS, $meta, "Compass");
	}
}
