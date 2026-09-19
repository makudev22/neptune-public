<?php


declare(strict_types=1);

namespace pocketmine\event\server;

use pocketmine\command\CommandSender;

/**
 * This event is called when a command is received over RCON.
 *
 * @deprecated Use CommandEvent instead.
 */
class RemoteServerCommandEvent extends ServerCommandEvent
{
	public function __construct(CommandSender $sender, string $command)
	{
		parent::__construct($sender, $command);
	}
}
