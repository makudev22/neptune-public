<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use InvalidArgumentException;
use pocketmine\entity\Skin;
use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when a player changes their skin in-game.
 */
class PlayerChangeSkinEvent extends PlayerEvent implements Cancellable
{
	/** @var Skin */
	private $oldSkin;
	/** @var Skin */
	private $newSkin;

	public function __construct(Player $player, Skin $oldSkin, Skin $newSkin)
	{
		$this->player = $player;
		$this->oldSkin = $oldSkin;
		$this->newSkin = $newSkin;
	}

	public function getOldSkin() : Skin
	{
		return $this->oldSkin;
	}

	public function getNewSkin() : Skin
	{
		return $this->newSkin;
	}

	/**
	 * @throws InvalidArgumentException if the specified skin is not valid
	 */
	public function setNewSkin(Skin $skin) : void
	{
		$skin->validate();
		$this->newSkin = $skin;
	}
}
