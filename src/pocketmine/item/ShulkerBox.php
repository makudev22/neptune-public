<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

class ShulkerBox extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::SHULKER_BOX, $meta, "Shulker Box");
	}

	public function getBlock() : Block
	{
		return BlockFactory::get(Block::SHULKER_BOX, $this->getDamage());
	}

	public function getMaxStackSize() : int
	{
		return 1;
	}
}
