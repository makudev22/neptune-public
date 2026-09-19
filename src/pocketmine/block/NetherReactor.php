<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\TieredTool;

class NetherReactor extends Solid
{
	protected $id = Block::NETHER_REACTOR;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		static $prefixes = [
			"",
			"Active ",
			"Used "
		];
		return ($prefixes[$this->meta] ?? "") . "Nether Reactor Core";
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_WOODEN;
	}

	public function getHardness() : float
	{
		return 3;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [
			ItemFactory::get(Item::IRON_INGOT, 0, 6),
			ItemFactory::get(Item::DIAMOND, 0, 3)
		];
	}
}
