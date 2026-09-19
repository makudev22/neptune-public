<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class UnknownBlock extends Transparent
{
	public function getHardness() : float
	{
		return 0;
	}

	public function canBePlaced() : bool
	{
		return false;
	}

	public function getDrops(Item $item) : array
	{
		return [];
	}
}
