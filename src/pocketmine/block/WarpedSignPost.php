<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class WarpedSignPost extends SignPost
{
	protected $id = Block::WARPED_STANDING_SIGN;

	protected $itemId = Item::WARPED_SIGN;
	public int $signWall = Block::WARPED_WALL_SIGN;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Warped Sign Post";
	}
}
