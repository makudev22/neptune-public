<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerToggleSneakEvent extends PlayerEvent implements Cancellable {
	public function __construct(
		Player $player,
		protected bool $isSneaking,
		protected bool $isSneakPressed
	){
		$this->player = $player;
	}

	public function isSneaking() : bool{
		return $this->isSneaking;
	}

	/**
	 * Returns whether the player is pressing the sneak key.
	 * The player may still be sneaking even if this is false due to gameplay mechanics (e.g. releasing sneak while in a 1.5 block high space).
	 */
	public function isSneakPressed() : bool{
		return $this->isSneakPressed;
	}
}
