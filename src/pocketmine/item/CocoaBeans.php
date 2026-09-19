<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;

class CocoaBeans extends Item
{
	public function getBlock() : Block
	{
		return Block::get(Block::COCOA_BLOCK);
	}
}
