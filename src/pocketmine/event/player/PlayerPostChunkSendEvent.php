<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\Player;

/**
 * Called after a player is sent a chunk as part of their view radius.
 */
final class PlayerPostChunkSendEvent extends PlayerEvent
{
	public function __construct(
		Player $player,
		private int $chunkX,
		private int $chunkZ
	) {
		$this->player = $player;
	}

	public function getChunkX() : int
	{
		return $this->chunkX;
	}

	public function getChunkZ() : int
	{
		return $this->chunkZ;
	}
}
