<?php


declare(strict_types=1);

namespace pocketmine\block;

class MangroveFence extends WoodenFence
{
	protected $id = self::MANGROVE_FENCE;

	public function getName() : string
	{
		return "Mangrove Fence";
	}
}
