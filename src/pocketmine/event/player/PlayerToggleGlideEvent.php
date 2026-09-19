<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerToggleGlideEvent extends PlayerEvent implements Cancellable
{
	/** @var bool */
	protected $isGlide;

	public function __construct(Player $player, bool $isGlide)
	{
		$this->player = $player;
		$this->isGlide = $isGlide;
	}

	public function isGlide() : bool
	{
		return $this->isGlide;
	}

}
