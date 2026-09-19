<?php


declare(strict_types=1);

namespace pocketmine\command;

use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\plugin\Plugin;

class PluginCommand extends Command implements PluginIdentifiableCommand
{
	/** @var Plugin */
	private $owningPlugin;

	/** @var CommandExecutor */
	private $executor;

	public function __construct(string $name, Plugin $owner)
	{
		parent::__construct($name);
		$this->owningPlugin = $owner;
		$this->executor = $owner;
		$this->usageMessage = "";
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{

		if (!$this->owningPlugin->isEnabled()) {
			return false;
		}

		if (!$this->testPermission($sender)) {
			return false;
		}

		$success = $this->executor->onCommand($sender, $this, $commandLabel, $args);

		if (!$success && $this->usageMessage !== "") {
			throw new InvalidCommandSyntaxException();
		}

		return $success;
	}

	public function getExecutor() : CommandExecutor
	{
		return $this->executor;
	}

	/**
	 * @return void
	 */
	public function setExecutor(CommandExecutor $executor)
	{
		$this->executor = $executor;
	}

	public function getPlugin() : Plugin
	{
		return $this->owningPlugin;
	}
}
