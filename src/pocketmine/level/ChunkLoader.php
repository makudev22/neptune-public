<?php


declare(strict_types=1);

namespace pocketmine\level;

use pocketmine\level\format\Chunk;
use pocketmine\math\Vector3;

/**
 * If you want to keep chunks loaded and receive notifications on a specific area,
 * extend this class and register it into Level. This will also tick chunks.
 *
 * Register {@link Level::registerChunkLoader()}
 * Unregister {@link Level::unregisterChunkLoader()}
 *
 * WARNING: When moving this object around in the world or destroying it,
 * be sure to free the existing references from Level, otherwise you'll leak memory.
 */
interface ChunkLoader
{

	public function getX() : float|int;

	public function getY() : float|int;

	public function getZ() : float|int;

	/**
	 * This method will be called when a Chunk is replaced by a new one
	 */
	public function onChunkChanged(Chunk $chunk) : void;

	/**
	 * This method will be called when a registered chunk is loaded
	 */
	public function onChunkLoaded(Chunk $chunk) : void;

	/**
	 * This method will be called when a registered chunk is unloaded
	 */
	public function onChunkUnloaded(Chunk $chunk) : void;

	/**
	 * This method will be called when a registered chunk is populated
	 * Usually it'll be sent with another call to onChunkChanged()
	 */
	public function onChunkPopulated(Chunk $chunk) : void;

	/**
	 * This method will be called when a block changes in a registered chunk
	 */
	public function onBlockChanged(Vector3 $block) : void;

}
