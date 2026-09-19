<?php


declare(strict_types=1);

namespace pocketmine\tile;

use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\inventory\FurnaceType;

class Smoker extends Furnace
{
	public function getDefaultName() : string
	{
		return "Smoker";
	}

	public function getFurnaceType() : FurnaceType
	{
		return FurnaceType::SMOKER;
	}

	protected function onStartSmelting() : void
	{
		$block = $this->getBlock();
		if($block->getId() === BlockIds::SMOKER){
			$this->getLevel()->setBlock($this, BlockFactory::get(BlockIds::LIT_SMOKER, $block->getDamage()), true);
		}
	}

	protected function onStopSmelting() : void
	{
		$block = $this->getBlock();
		if($block->getId() === BlockIds::LIT_SMOKER){
			$this->getLevel()->setBlock($this, BlockFactory::get(BlockIds::SMOKER, $block->getDamage()), true);
		}
	}
}
