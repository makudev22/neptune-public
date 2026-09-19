<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class MangroveWallSign extends WallSign
{
	protected $id = Block::MANGROVE_WALL_SIGN;

	protected $itemId = Item::MANGROVE_SIGN;
	public int $signWall = Block::MANGROVE_WALL_SIGN;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Mangrove Wall Sign";
	}
}
