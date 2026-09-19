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

class OpCommand extends VanillaCommand
{
	public function __construct(string $name)
	{
		parent::__construct($name, "%pocketmine.command.op.description", "%commands.op.usage", [], [
			new CommandOverload(false, [
				CommandParameter::standard("player", AvailableCommandsPacket::ARG_TYPE_TARGET)
			])
		]);
		$this->setPermission("pocketmine.command.op.take");
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
		if (!Player::isValidUserName($name)) {
			throw new InvalidCommandSyntaxException();
		}

		$player = $sender->getServer()->getOfflinePlayer($name);
		Command::broadcastCommandMessage($sender, new TranslationContainer("commands.op.success", [$player->getName()]));
		if ($player instanceof Player) {
			$player->sendMessage(TextFormat::GRAY . "You are now op!");
		}
		$player->setOp(true);
		return true;
	}
}
