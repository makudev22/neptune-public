<?php


declare(strict_types=1);

namespace pocketmine\event\player\cheat;

use pocketmine\event\Cancellable;
use pocketmine\math\Vector3;
use pocketmine\Player;

/**
 * Called when a player attempts to perform movement cheats such as clipping through blocks.
 */
class PlayerIllegalMoveEvent extends PlayerCheatEvent implements Cancellable{

	private Vector3 $attemptedPosition;
	private Vector3 $originalPosition;
	private Vector3 $expectedPosition;

	public function __construct(Player $player, Vector3 $attemptedPosition, Vector3 $originalPosition){
		$this->player = $player;
		$this->attemptedPosition = $attemptedPosition;
		$this->originalPosition = $originalPosition;
		$this->expectedPosition = $player->asVector3();
	}

	/**
	 * Returns the position the player attempted to move to.
	 */
	public function getAttemptedPosition() : Vector3{
		return $this->attemptedPosition;
	}

	public function getOriginalPosition() : Vector3{
		return $this->originalPosition;
	}

	public function getExpectedPosition() : Vector3{
		return $this->expectedPosition;
	}
}
