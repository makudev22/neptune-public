<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\Player;

class DeepslateRedstoneOre extends RedstoneOre
{
	protected $id = self::DEEPSLATE_REDSTONE_ORE;

	public function getName() : string
	{
		return "Deepslate Redstone Ore";
	}

	public function getHardness() : float
	{
		return 4.5;
	}

	public function onActivate(Item $item, Player $player = null) : bool
	{
		$this->getLevel()->setBlock($this, BlockFactory::get(BlockIds::LIT_DEEPSLATE_REDSTONE_ORE, $this->meta));
		return false; //this shouldn't prevent block placement
	}

	public function onNearbyBlockChange() : void
	{
		$this->getLevel()->setBlock($this, BlockFactory::get(BlockIds::LIT_DEEPSLATE_REDSTONE_ORE, $this->meta));
	}
}
