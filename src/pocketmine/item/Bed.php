<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

class Bed extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::BED, $meta, "Bed");
	}

	public function getBlock() : Block
	{
		return BlockFactory::get(Block::BED_BLOCK, $this->meta);
	}

	public function getMaxStackSize() : int
	{
		return 1;
	}
}
