<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

class StringItem extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::STRING, $meta, "String");
	}

	public function getBlock() : Block
	{
		return BlockFactory::get(Block::TRIPWIRE);
	}
}
