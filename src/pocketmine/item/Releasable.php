<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\Player;

/**
 * Interface implemented by objects that can be used.
 */
interface Releasable
{
	public function canStartUsingItem(Player $player) : bool;

}
