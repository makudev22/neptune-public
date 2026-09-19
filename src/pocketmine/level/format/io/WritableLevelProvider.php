<?php


declare(strict_types=1);

namespace pocketmine\level\format\io;

use pocketmine\level\format\Chunk;

interface WritableLevelProvider extends LevelProvider
{
	/**
	 * Saves a chunk (usually to disk).
	 */
	public function saveChunk(Chunk $chunk) : void;
}
