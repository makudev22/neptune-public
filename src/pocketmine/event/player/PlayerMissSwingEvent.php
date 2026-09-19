<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when a player attempts to perform the attack action (left-click) without a target entity.
 */
class PlayerMissSwingEvent extends PlayerEvent implements Cancellable
{
	public function __construct(Player $player)
	{
		$this->player = $player;
	}
}
