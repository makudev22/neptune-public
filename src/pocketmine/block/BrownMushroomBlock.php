<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

use function mt_rand;

class BrownMushroomBlock extends RedMushroomBlock
{
	protected $id = Block::BROWN_MUSHROOM_BLOCK;

	public function getName() : string
	{
		return "Brown Mushroom Block";
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [
			Item::get(Item::BROWN_MUSHROOM, 0, mt_rand(0, 2))
		];
	}
}
