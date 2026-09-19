<?php


declare(strict_types=1);

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\TranslationContainer;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandOverload;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\Server;

use function count;

class DefaultGamemodeCommand extends VanillaCommand
{
	public function __construct(string $name)
	{
		parent::__construct($name, "%pocketmine.command.defaultgamemode.description", "%commands.defaultgamemode.usage", [], [
			new CommandOverload(false, [
				CommandParameter::enum("gameMode", new CommandEnum("defaultGameMode", [
					"creative", "survival", "adventure"
				]), 0),
			]),
			new CommandOverload(false, [
				CommandParameter::standard("gameMode", AvailableCommandsPacket::ARG_TYPE_INT),
			])
		]);
		$this->setPermission("pocketmine.command.defaultgamemode");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if (!$this->testPermission($sender)) {
			return true;
		}

		if (count($args) === 0) {
			throw new InvalidCommandSyntaxException();
		}

		$gameMode = Server::getGamemodeFromString($args[0]);

		if ($gameMode !== -1) {
			$sender->getServer()->setConfigInt("gamemode", $gameMode);
			$sender->sendMessage(new TranslationContainer("commands.defaultgamemode.success", [Server::getGamemodeString($gameMode)]));
		} else {
			$sender->sendMessage("Unknown game mode");
		}

		return true;
	}
}
