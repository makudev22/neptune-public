<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\ItemIds;

class WarpedWallSign extends WallSign
{
	protected $id = BlockIds::WARPED_WALL_SIGN;

	protected $itemId = ItemIds::WARPED_SIGN;
	public int $signWall = BlockIds::WARPED_WALL_SIGN;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Warped Wall Sign";
	}
}
