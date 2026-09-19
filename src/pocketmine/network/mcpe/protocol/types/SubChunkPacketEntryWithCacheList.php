<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

final class SubChunkPacketEntryWithCacheList
{
	/**
	 * @param SubChunkPacketEntryWithCache[] $entries
	 */
	public function __construct(
		private array $entries
	) {
	}

	/**
	 * @return SubChunkPacketEntryWithCache[]
	 */
	public function getEntries() : array
	{
		return $this->entries;
	}
}
