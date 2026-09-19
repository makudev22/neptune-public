<?php


declare(strict_types=1);

namespace pocketmine\item;

class Book extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::BOOK, $meta, "Book");
	}

	public function getEnchantAbility() : int{
		return 1;
	}
}
