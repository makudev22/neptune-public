<?php


declare(strict_types=1);

namespace pocketmine\tile;

use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\inventory\FurnaceType;

class BlastFurnace extends Furnace
{
	public function getDefaultName() : string
	{
		return "Blast Furnace";
	}

	public function getFurnaceType() : FurnaceType
	{
		return FurnaceType::BLAST_FURNACE;
	}

	protected function onStartSmelting() : void
	{
		$block = $this->getBlock();
		if($block->getId() === BlockIds::BLAST_FURNACE){
			$this->getLevel()->setBlock($this, BlockFactory::get(BlockIds::LIT_BLAST_FURNACE, $block->getDamage()), true);
		}
	}

	protected function onStopSmelting() : void
	{
		$block = $this->getBlock();
		if($block->getId() === BlockIds::LIT_BLAST_FURNACE){
			$this->getLevel()->setBlock($this, BlockFactory::get(BlockIds::BLAST_FURNACE, $block->getDamage()), true);
		}
	}
}
