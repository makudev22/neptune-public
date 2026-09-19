<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when a player connects to the server, prior to authentication taking place.
 * Cancelling this event will cause the player to be disconnected with the kick message set.
 *
 * This event should be used to decide if the player may continue to login to the server. Do things like checking
 * bans, whitelisting, server-full etc here.
 *
 * WARNING: Any information about the player CANNOT be trusted at this stage, because they are not authenticated and
 * could be a hacker posing as another player.
 *
 * WARNING: Due to internal bad architecture, the player is not fully constructed at this stage, and errors might occur
 * when calling API methods on the player. Tread with caution.
 */
class PlayerPreLoginEvent extends PlayerEvent implements Cancellable
{
	/** @var string */
	protected $kickMessage;

	public function __construct(Player $player, string $kickMessage)
	{
		$this->player = $player;
		$this->kickMessage = $kickMessage;
	}

	public function setKickMessage(string $kickMessage) : void
	{
		$this->kickMessage = $kickMessage;
	}

	public function getKickMessage() : string
	{
		return $this->kickMessage;
	}
}
