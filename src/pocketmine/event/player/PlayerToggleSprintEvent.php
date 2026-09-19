<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerToggleSprintEvent extends PlayerEvent implements Cancellable
{
	/** @var bool */
	protected $isSprinting;

	public function __construct(Player $player, bool $isSprinting)
	{
		$this->player = $player;
		$this->isSprinting = $isSprinting;
	}

	public function isSprinting() : bool
	{
		return $this->isSprinting;
	}
}
