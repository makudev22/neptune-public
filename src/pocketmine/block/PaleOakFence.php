<?php


declare(strict_types=1);

namespace pocketmine\block;

class PaleOakFence extends WoodenFence
{
	protected $id = self::PALE_OAK_FENCE;

	public function getName() : string
	{
		return "Pale Oak Fence";
	}
}
