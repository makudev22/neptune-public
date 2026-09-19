<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when a player does an animation
 */
class PlayerAnimationEvent extends PlayerEvent implements Cancellable
{
	/** @var int */
	private $animationType;

	public function __construct(Player $player, int $animation)
	{
		$this->player = $player;
		$this->animationType = $animation;
	}

	public function getAnimationType() : int
	{
		return $this->animationType;
	}
}
