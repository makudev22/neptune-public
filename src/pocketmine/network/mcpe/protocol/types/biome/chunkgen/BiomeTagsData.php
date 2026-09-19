<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\biome\chunkgen;

use pocketmine\network\mcpe\NetworkBinaryStream;
use function count;

final class BiomeTagsData
{
	/**
	 * @param int[] $indexes
	 */
	public function __construct(
		private array $indexes,
	) {
	}

	/**
	 * @return int[]
	 */
	public function getIndexes() : array
	{
		return $this->indexes;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$tags = [];

		for ($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i) {
			$tags[] = $in->getLShort();
		}

		return new self($tags);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putUnsignedVarInt(count($this->indexes));
		foreach ($this->indexes as $tag) {
			$out->putLShort($tag);
		}
	}
}
