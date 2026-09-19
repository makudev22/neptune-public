<?php


declare(strict_types=1);

namespace pocketmine\command;

interface CommandMap
{
	/**
	 * @param Command[] $commands
	 *
	 * @return void
	 */
	public function registerAll(string $fallbackPrefix, array $commands);

	public function register(string $fallbackPrefix, Command $command, string $label = null) : bool;

	public function dispatch(CommandSender $sender, string $cmdLine) : bool;

	/**
	 * @return void
	 */
	public function clearCommands();

	/**
	 * @return Command|null
	 */
	public function getCommand(string $name);

}
