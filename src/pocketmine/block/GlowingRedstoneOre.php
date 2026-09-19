<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\Player;

class GlowingRedstoneOre extends RedstoneOre
{
	protected $id = self::GLOWING_REDSTONE_ORE;

	protected $itemId = self::REDSTONE_ORE;

	public function getName() : string
	{
		return "Glowing Redstone Ore";
	}

	public function getLightLevel() : int
	{
		return 9;
	}

	public function onActivate(Item $item, Player $player = null) : bool
	{
		return false;
	}

	public function onNearbyBlockChange() : void
	{

	}

	public function ticksRandomly() : bool
	{
		return true;
	}

	public function onRandomTick() : void
	{
		$this->getLevel()->setBlock($this, BlockFactory::get(Block::REDSTONE_ORE, $this->meta), false, false);
	}
}
