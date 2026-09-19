<?php


declare(strict_types=1);

namespace pocketmine\item\trim;

use pocketmine\item\Item;

final readonly class TrimPatternData
{
	public function __construct(
		public string $name,
		public Item $item,
	) {}
}
