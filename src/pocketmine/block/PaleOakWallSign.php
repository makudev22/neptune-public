<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class PaleOakWallSign extends WallSign
{
	protected $id = Block::PALE_OAK_WALL_SIGN;

	protected $itemId = Item::PALE_OAK_SIGN;
	public int $signWall = Block::PALE_OAK_WALL_SIGN;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Pale Oak Wall Sign";
	}
}
