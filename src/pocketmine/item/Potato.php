<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

class Potato extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::POTATO, $meta, "Potato");
	}

	public function getBlock() : Block
	{
		return BlockFactory::get(Block::POTATO_BLOCK);
	}

	public function getFoodRestore() : int
	{
		return 1;
	}

	public function getSaturationRestore() : float
	{
		return 0.6;
	}
}
