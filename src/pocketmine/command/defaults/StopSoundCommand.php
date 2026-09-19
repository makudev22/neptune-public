<?php


declare(strict_types=1);

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\TranslationContainer;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\StopSoundPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandOverload;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\utils\TextFormat;

use function strlen;

class StopSoundCommand extends VanillaCommand
{
	public function __construct(string $name)
	{
		parent::__construct($name, "Stops a sound or all sounds", "/stopsound <player: target> [sound: string]", [], [
			new CommandOverload(false, [
				CommandParameter::standard("player", AvailableCommandsPacket::ARG_TYPE_TARGET),
				CommandParameter::standard("sound", AvailableCommandsPacket::ARG_TYPE_STRING, 0, true)
			])
		]);

		$this->setPermission("pocketmine.command.stopsound");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if (!$this->testPermission($sender)) {
			return true;
		}

		if (empty($args)) {
			throw new InvalidCommandSyntaxException();
		}

		$player = $sender->getServer()->getPlayer($args[0]);

		if ($player === null) {
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.player.notFound"));
			return true;
		}

		$soundName = $args[1] ?? "";
		$stopAll = strlen($soundName) === 0;

		$pk = new StopSoundPacket();
		$pk->soundName = $soundName;
		$pk->stopAll = $stopAll;
		$player->sendDataPacket($pk);

		$message = $stopAll ? new TranslationContainer("commands.stopsound.success.all", [$player->getName()]) : new TranslationContainer("commands.stopsound.success", [
			$soundName, $player->getName()
		]);
		$player->sendMessage($message);

		return true;
	}
}
