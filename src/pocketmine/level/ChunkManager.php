<?php


declare(strict_types=1);

namespace pocketmine\level;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\level\format\Chunk;
use pocketmine\tile\Tile;

interface ChunkManager
{
	/**
	 * Returns a Block object representing the block state at the given coordinates.
	 */
	public function getBlockAt(int $x, int $y, int $z) : Block;

	/**
	 * Sets the block at the given coordinates to the block state specified.
	 *
	 * @throws \InvalidArgumentException
	 */
	public function setBlockAt(int $x, int $y, int $z, Block $block) : bool;

	public function addEntity(Entity $entity) : void;

	public function addTile(Tile $tile) : void;

	public function getChunk(int $chunkX, int $chunkZ) : ?Chunk;

	public function setChunk(int $chunkX, int $chunkZ, Chunk $chunk = null) : void;

	/**
	 * Gets the level seed
	 */
	public function getSeed() : int;

	/**
	 * Returns the height of the world
	 */
	public function getWorldHeight() : int;

	/**
	 * Returns whether the specified coordinates are within the valid world boundaries, taking world format limitations
	 * into account.
	 */
	public function isInWorld(int $x, int $y, int $z) : bool;
}
