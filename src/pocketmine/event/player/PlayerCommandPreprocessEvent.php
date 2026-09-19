<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when a player runs a command or chats, early in the process
 *
 * You don't want to use this except for a few cases like logging commands,
 * blocking commands on certain places, or applying modifiers.
 *
 * The message contains a slash at the start
 */
class PlayerCommandPreprocessEvent extends PlayerEvent implements Cancellable {
	protected string $message;

	public function __construct(Player $player, string $message){
		$this->player = $player;
		$this->message = $message;
	}

	public function getMessage() : string{
		return $this->message;
	}
}
