<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

class WheatSeeds extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::WHEAT_SEEDS, $meta, "Wheat Seeds");
	}

	public function getBlock() : Block
	{
		return BlockFactory::get(Block::WHEAT_BLOCK);
	}
}
