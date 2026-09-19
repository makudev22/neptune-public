<?php


declare(strict_types=1);

namespace pocketmine\item;

class Bowl extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::BOWL, $meta, "Bowl");
	}

	//TODO: check fuel
}
