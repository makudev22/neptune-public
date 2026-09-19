<?php


declare(strict_types=1);

namespace pocketmine\block;

class CherryFence extends WoodenFence
{
	protected $id = self::CHERRY_FENCE;

	public function getName() : string
	{
		return "Cherry Fence";
	}
}
