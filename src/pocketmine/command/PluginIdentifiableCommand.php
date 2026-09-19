<?php


declare(strict_types=1);

namespace pocketmine\command;

use pocketmine\plugin\Plugin;

interface PluginIdentifiableCommand
{
	public function getPlugin() : Plugin;
}
