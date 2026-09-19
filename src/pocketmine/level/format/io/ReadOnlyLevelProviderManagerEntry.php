<?php


declare(strict_types=1);

namespace pocketmine\level\format\io;

class ReadOnlyLevelProviderManagerEntry extends LevelProviderManagerEntry
{
	public function __construct(
		\Closure $isValid,
		private \Closure $fromPath
	) {
		parent::__construct($isValid);
	}

	public function fromPath(string $path) : LevelProvider
	{
		return ($this->fromPath)($path);
	}
}
