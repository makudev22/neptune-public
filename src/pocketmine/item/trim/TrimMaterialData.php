<?php


declare(strict_types=1);

namespace pocketmine\item\trim;

use pocketmine\item\Item;

final readonly class TrimMaterialData
{
	public function __construct(
		public string $name,
		public string $color,
		public Item $item,
	) {}
}
