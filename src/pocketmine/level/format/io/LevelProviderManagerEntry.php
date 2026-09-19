<?php


declare(strict_types=1);

namespace pocketmine\level\format\io;

abstract class LevelProviderManagerEntry
{
	protected function __construct(
		protected \Closure $isValid
	) {
	}

	/**
	 * Tells if the path is a valid world.
	 * This must tell if the current format supports opening the files in the directory
	 */
	public function isValid(string $path) : bool
	{
		return ($this->isValid)($path);
	}

	abstract public function fromPath(string $path) : LevelProvider;
}
