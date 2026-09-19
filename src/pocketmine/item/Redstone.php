<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

class Redstone extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::REDSTONE, $meta, "Redstone");
	}

	public function getBlock() : Block
	{
		return BlockFactory::get(Block::REDSTONE_WIRE);
	}
}
