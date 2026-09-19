<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

class BeetrootSeeds extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::BEETROOT_SEEDS, $meta, "Beetroot Seeds");
	}

	public function getBlock() : Block
	{
		return BlockFactory::get(Block::BEETROOT_BLOCK);
	}
}
