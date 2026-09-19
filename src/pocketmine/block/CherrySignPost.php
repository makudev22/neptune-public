<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class CherrySignPost extends SignPost
{
	protected $id = Block::CHERRY_STANDING_SIGN;

	protected $itemId = Item::CHERRY_SIGN;
	public int $signWall = Block::CHERRY_WALL_SIGN;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Cherry Sign Post";
	}
}
