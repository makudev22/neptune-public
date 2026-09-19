<?php


declare(strict_types=1);

namespace pocketmine\event\level;

use pocketmine\level\format\Chunk;
use pocketmine\level\Level;

/**
 * Called when a Chunk is loaded or newly created by the world generator.
 */
class ChunkLoadEvent extends ChunkEvent
{
	public function __construct(
		Level $level,
		Chunk $chunk,
		private bool $newChunk
	) {
		parent::__construct($level, $chunk);
	}

	/**
	 * Returns whether the chunk is newly generated.
	 * If false, the chunk was loaded from storage.
	 */
	public function isNewChunk() : bool
	{
		return $this->newChunk;
	}
}
