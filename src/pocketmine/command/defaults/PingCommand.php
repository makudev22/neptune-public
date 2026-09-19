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

use function count;

class PingCommand extends VanillaCommand
{
	public function __construct(string $name)
	{
		parent::__construct($name, "%pocketmine.command.ping.description", "%commands.ping.usage", [], [
			new CommandOverload(false, [
				CommandParameter::standard("player", AvailableCommandsPacket::ARG_TYPE_TARGET, 0, true)
			])
		]);
		$this->setPermission("pocketmine.command.ping");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if (!$this->testPermission($sender)) {
			return true;
		}

		if (count($args) >= 2) {
			throw new InvalidCommandSyntaxException();
		}

		$target = null;

		if (count($args) === 1) {
			$target = $sender->getServer()->getPlayer($args[0]);
		} else {
			if ($sender instanceof Player) {
				$target = $sender;
			} else {
				throw new InvalidCommandSyntaxException();
			}
		}

		if ($target === null) {
			$sender->sendMessage(new TranslationContainer("command.generic.player.notFound"));
			return true;
		}

		$ping = $target->getPing();
		$color = ($ping < 150 ? TextFormat::GREEN : ($ping < 250 ? TextFormat::GOLD : TextFormat::RED));
		$sender->sendMessage($target->getName() . "'s Ping: " . $color . $ping . "ms");

		return true;
	}
}
