<?php


declare(strict_types=1);

namespace pocketmine\block;

class BambooFence extends WoodenFence
{
	protected $id = self::BAMBOO_FENCE;

	public function getName() : string
	{
		return "Bamboo Fence";
	}
}
