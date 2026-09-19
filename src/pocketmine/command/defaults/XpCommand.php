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

use function abs;
use function count;
use function rtrim;
use function strcasecmp;
use function substr;

class XpCommand extends VanillaCommand
{
	public function __construct(string $name)
	{
		parent::__construct($name, "%pocketmine.command.xp.description", 'pocketmine.command.xp.usage', [], [
			new CommandOverload(false, [
				CommandParameter::standard("amount", AvailableCommandsPacket::ARG_TYPE_INT),
				CommandParameter::standard("player", AvailableCommandsPacket::ARG_TYPE_TARGET, 0, true)
			])
		]);

		$this->setPermission("pocketmine.command.xp");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if (!$this->testPermission($sender)) {
			return true;
		}

		if (count($args) < 1) {
			throw new InvalidCommandSyntaxException();
		}

		if (count($args) < 2) {
			if (!($sender instanceof Player)) {
				throw new InvalidCommandSyntaxException();
			}
			$player = $sender;
		} else {
			$player = $sender->getServer()->getPlayer($args[1]);
		}

		$xp = $args[0];

		if ($player instanceof Player) {
			$isim = $player->getName();
			if (strcasecmp(substr($xp, -1), "L") == 0) { // Level
				$xp = (int) rtrim($xp, "Ll");
				if ($xp > 0) {
					$player->addXpLevels($xp);
					$sender->sendMessage(new TranslationContainer("commands.xp.success.levels", [$xp, $isim]));
					return true;
				} elseif ($xp < 0) {
					$xp = abs($xp);
					$player->subtractXpLevels($xp);
					$sender->sendMessage(new TranslationContainer("commands.xp.success.negative.levels", [$xp, $isim]));
					return true;
				}
			} else {
				$xp = (int) $xp;
				if ($xp > 0) {
					$player->addXp($xp);
					$sender->sendMessage(new TranslationContainer("commands.xp.success", [$xp, $isim]));
					return true;
				} elseif ($xp < 0) {
					$sender->sendMessage(new TranslationContainer("commands.xp.failure.withdrawXp"));
					return true;
				}
			}
		} else {
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.player.notFound"));
			return false;
		}

		throw new InvalidCommandSyntaxException();
	}
}
