<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\ItemIds;

class BambooWallSign extends WallSign
{
	protected $id = BlockIds::BAMBOO_WALL_SIGN;

	protected $itemId = ItemIds::BAMBOO_SIGN;
	public int $signWall = BlockIds::BAMBOO_WALL_SIGN;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Bamboo Wall Sign";
	}
}
