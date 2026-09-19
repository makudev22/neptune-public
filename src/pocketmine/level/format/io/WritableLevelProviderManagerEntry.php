<?php


declare(strict_types=1);

namespace pocketmine\level\format\io;

use pocketmine\level\LevelCreationOptions;

final class WritableLevelProviderManagerEntry extends LevelProviderManagerEntry
{
	public function __construct(
		\Closure $isValid,
		private \Closure $fromPath,
		private \Closure $generate
	) {
		parent::__construct($isValid);
	}

	public function fromPath(string $path) : WritableLevelProvider
	{
		return ($this->fromPath)($path);
	}

	/**
	 * Generates world manifest files and any other things needed to initialize a new world on disk
	 */
	public function generate(string $path, string $name, LevelCreationOptions $options) : void
	{
		($this->generate)($path, $name, $options);
	}
}
