<?php


declare(strict_types=1);

namespace pocketmine\scheduler;

use pocketmine\plugin\Plugin;

/**
 * Base class for plugin tasks. Allows the Server to delete them easily when needed
 */
abstract class PluginTask extends Task
{
	/** @var Plugin */
	protected $owner;

	public function __construct(Plugin $owner)
	{
		$this->owner = $owner;
	}

	final public function getOwner() : Plugin
	{
		return $this->owner;
	}
}
