<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerToggleFlightEvent extends PlayerEvent implements Cancellable
{
	/** @var bool */
	protected $isFlying;

	public function __construct(Player $player, bool $isFlying)
	{
		$this->player = $player;
		$this->isFlying = $isFlying;
	}

	public function isFlying() : bool
	{
		return $this->isFlying;
	}
}
