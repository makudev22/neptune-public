<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

final class SubChunkPacketEntryWithoutCacheList
{
	/**
	 * @param SubChunkPacketEntryWithoutCache[] $entries
	 */
	public function __construct(
		private array $entries
	) {
	}

	/**
	 * @return SubChunkPacketEntryWithoutCache[]
	 */
	public function getEntries() : array
	{
		return $this->entries;
	}
}
