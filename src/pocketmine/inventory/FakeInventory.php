<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\Player;

interface FakeInventory
{
	/**
	 * @return int[]
	 */
	public function getUIOffsets(?Player $player) : array;
}
