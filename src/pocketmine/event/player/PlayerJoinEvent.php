<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\lang\TextContainer;
use pocketmine\Player;

/**
 * Called when the player spawns in the world after logging in, when they first see the terrain.
 *
 * Note: A lot of data is sent to the player between login and this event. Disconnecting the player during this event
 * will cause this data to be wasted. Prefer disconnecting at login-time if possible to minimize bandwidth wastage.
 * @see PlayerLoginEvent
 */
class PlayerJoinEvent extends PlayerEvent
{
	/** @var string|TextContainer */
	protected $joinMessage;

	/**
	 * PlayerJoinEvent constructor.
	 *
	 * @param TextContainer|string $joinMessage
	 */
	public function __construct(Player $player, $joinMessage)
	{
		$this->player = $player;
		$this->joinMessage = $joinMessage;
	}

	/**
	 * @param string|TextContainer $joinMessage
	 */
	public function setJoinMessage($joinMessage) : void
	{
		$this->joinMessage = $joinMessage;
	}

	/**
	 * @return string|TextContainer
	 */
	public function getJoinMessage()
	{
		return $this->joinMessage;
	}
}
