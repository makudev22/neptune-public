<?php


declare(strict_types=1);

namespace pocketmine\command;

use pocketmine\lang\TextContainer;
use pocketmine\permission\Permissible;
use pocketmine\Server;

interface CommandSender extends Permissible
{
	/**
	 * @param TextContainer|string $message
	 *
	 * @return void
	 */
	public function sendMessage($message);

	public function sendMessagef(string $format, mixed ...$args) : void;

	/**
	 * @return Server
	 */
	public function getServer();

	public function getName() : string;

	/**
	 * Returns the line height of the command-sender's screen. Used for determining sizes for command output pagination
	 * such as in the /help command.
	 */
	public function getScreenLineHeight() : int;

	/**
	 * Sets the line height used for command output pagination for this command sender. `null` will reset it to default.
	 *
	 * @return void
	 */
	public function setScreenLineHeight(int $height = null);
}
