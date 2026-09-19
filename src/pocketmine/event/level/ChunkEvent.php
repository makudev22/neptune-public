<?php


declare(strict_types=1);

namespace pocketmine\event\level;

use pocketmine\level\format\Chunk;
use pocketmine\level\Level;

/**
 * Chunk-related events
 */
abstract class ChunkEvent extends LevelEvent
{
	public function __construct(
		Level $level,
		private Chunk $chunk
	) {
		parent::__construct($level);
	}

	public function getChunk() : Chunk
	{
		return $this->chunk;
	}
}
