<?php


declare(strict_types=1);

namespace pocketmine\block;

class TrappedChest extends Chest
{
	//TODO: Redstone!

	protected $id = self::TRAPPED_CHEST;

	public function getName() : string
	{
		return "Trapped Chest";
	}
}
