<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class CrimsonWallSign extends WallSign
{
	protected $id = Block::CRIMSON_WALL_SIGN;

	protected $itemId = Item::CRIMSON_SIGN;
	public int $signWall = Block::CRIMSON_WALL_SIGN;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Crimson Wall Sign";
	}
}
