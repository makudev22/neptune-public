<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

use function mt_rand;

class RedMushroomBlock extends Solid
{
	protected $id = Block::RED_MUSHROOM_BLOCK;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Red Mushroom Block";
	}

	public function getHardness() : float
	{
		return 0.2;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_AXE;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [
			ItemFactory::get(Item::RED_MUSHROOM, 0, mt_rand(0, 2))
		];
	}
}
