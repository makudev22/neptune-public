<?php


declare(strict_types=1);

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
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
use function trim;

class KickCommand extends VanillaCommand
{
	public function __construct(string $name)
	{
		parent::__construct($name, "%pocketmine.command.kick.description", "%commands.kick.usage", [], [
			new CommandOverload(false, [
				CommandParameter::standard("player", AvailableCommandsPacket::ARG_TYPE_TARGET),
				CommandParameter::standard("reason", AvailableCommandsPacket::ARG_TYPE_RAWTEXT, 0, true)
			])
		]);
		$this->setPermission("pocketmine.command.kick");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if (!$this->testPermission($sender)) {
			return true;
		}

		if (count($args) === 0) {
			throw new InvalidCommandSyntaxException();
		}

		$name = array_shift($args);
		$reason = trim(implode(" ", $args));

		if (($player = $sender->getServer()->getPlayer($name)) instanceof Player) {
			$player->kick($reason);
			if ($reason !== "") {
				Command::broadcastCommandMessage($sender, new TranslationContainer("commands.kick.success.reason", [$player->getName(), $reason]));
			} else {
				Command::broadcastCommandMessage($sender, new TranslationContainer("commands.kick.success", [$player->getName()]));
			}
		} else {
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.player.notFound"));
		}

		return true;
	}
}
