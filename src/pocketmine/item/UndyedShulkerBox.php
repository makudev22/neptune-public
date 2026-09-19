<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

class UndyedShulkerBox extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::UNDYED_SHULKER_BOX, $meta, "Undyed Shulker Box");
	}

	public function getBlock() : Block
	{
		return BlockFactory::get(Block::UNDYED_SHULKER_BOX);
	}

	public function getMaxStackSize() : int
	{
		return 1;
	}
}
