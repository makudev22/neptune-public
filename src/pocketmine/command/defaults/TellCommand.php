<?php


declare(strict_types=1);

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\TranslationContainer;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandOverload;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

use function array_shift;
use function count;
use function implode;

class TellCommand extends VanillaCommand
{
	public function __construct(string $name)
	{
		parent::__construct($name, "%pocketmine.command.tell.description", "%commands.message.usage", ["w", "msg"], [
			new CommandOverload(false, [
				CommandParameter::standard("player", AvailableCommandsPacket::ARG_TYPE_TARGET),
				CommandParameter::standard("message", AvailableCommandsPacket::ARG_TYPE_MESSAGE)
			])
		]);
		$this->setPermission("pocketmine.command.tell");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if (!$this->testPermission($sender)) {
			return true;
		}

		if (count($args) < 2) {
			throw new InvalidCommandSyntaxException();
		}

		$player = $sender->getServer()->getPlayer(array_shift($args));

		if ($player === $sender) {
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.message.sameTarget"));
			return true;
		}

		if ($player instanceof Player) {
			$sender->sendMessage("[{$sender->getName()} -> {$player->getDisplayName()}] " . implode(" ", $args));
			$name = $sender instanceof Player ? $sender->getDisplayName() : $sender->getName();
			$player->sendMessage("[$name -> {$player->getName()}] " . implode(" ", $args));
		} else {
			$sender->sendMessage(new TranslationContainer("commands.generic.player.notFound"));
		}

		return true;
	}
}
