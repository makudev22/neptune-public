<?php


declare(strict_types=1);

namespace pocketmine\command;

use pocketmine\lang\TextContainer;

use function sprintf;
use function trim;

class RemoteConsoleCommandSender extends ConsoleCommandSender
{
	/** @var string */
	private $messages = "";

	public function sendMessage($message)
	{
		if ($message instanceof TextContainer) {
			$message = $this->getServer()->getLanguage()->translate($message);
		} else {
			$message = $this->getServer()->getLanguage()->translateString($message);
		}

		$this->messages .= trim($message, "\r\n") . "\n";
	}

	public function sendMessagef(string $format, mixed ...$args) : void
	{
		$this->sendMessage(sprintf($format, ...$args));
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->messages;
	}

	public function getName() : string
	{
		return "Rcon";
	}
}
