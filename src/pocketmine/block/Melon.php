<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

use function mt_rand;

class Melon extends Transparent
{
	protected $id = self::MELON_BLOCK;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Melon Block";
	}

	public function getHardness() : float
	{
		return 1;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_AXE;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [
			ItemFactory::get(Item::MELON_SLICE, 0, mt_rand(3, 7))
		];
	}
}
