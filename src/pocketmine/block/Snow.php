<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\TieredTool;

class Snow extends Solid
{
	protected $id = self::SNOW_BLOCK;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getHardness() : float
	{
		return 0.2;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_SHOVEL;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_WOODEN;
	}

	public function getName() : string
	{
		return "Snow Block";
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [
			ItemFactory::get(Item::SNOWBALL, 0, 4)
		];
	}
}
