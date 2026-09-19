<?php


declare(strict_types=1);

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandOverload;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;

use function date;

class DumpMemoryCommand extends VanillaCommand
{
	public function __construct(string $name)
	{
		parent::__construct($name, "Dumps the memory", "/$name [path]", [], [
			new CommandOverload(false, [
				CommandParameter::standard("path", AvailableCommandsPacket::ARG_TYPE_VALUE, 0, true)
			])
		]);
		$this->setPermission("pocketmine.command.dumpmemory");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if (!$this->testPermission($sender)) {
			return true;
		}

		$sender->getServer()->getMemoryManager()->dumpServerMemory($args[0] ?? ($sender->getServer()->getDataPath() . "/memory_dumps/" . date("D_M_j-H.i.s-T_Y")), 48, 80);
		return true;
	}
}
