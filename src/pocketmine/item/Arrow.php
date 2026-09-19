<?php


declare(strict_types=1);

namespace pocketmine\item;

class Arrow extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::ARROW, $meta, "Arrow");
	}
}
