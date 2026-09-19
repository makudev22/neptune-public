<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

class Carrot extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::CARROT, $meta, "Carrot");
	}

	public function getBlock() : Block
	{
		return BlockFactory::get(Block::CARROT_BLOCK);
	}

	public function getFoodRestore() : int
	{
		return 3;
	}

	public function getSaturationRestore() : float
	{
		return 4.8;
	}
}
