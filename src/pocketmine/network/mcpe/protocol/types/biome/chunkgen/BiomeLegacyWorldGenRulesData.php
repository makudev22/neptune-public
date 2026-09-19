<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\biome\chunkgen;

use pocketmine\network\mcpe\NetworkBinaryStream;
use function count;

final class BiomeLegacyWorldGenRulesData
{
	/**
	 * @param BiomeConditionalTransformationData[] $legacyPreHills
	 */
	public function __construct(
		private array $legacyPreHills,
	) {
	}

	/**
	 * @return BiomeConditionalTransformationData[]
	 */
	public function getLegacyPreHills() : array
	{
		return $this->legacyPreHills;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$legacyPreHills = [];
		for ($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i) {
			$legacyPreHills[] = BiomeConditionalTransformationData::read($in);
		}

		return new self(
			$legacyPreHills,
		);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putUnsignedVarInt(count($this->legacyPreHills));
		foreach ($this->legacyPreHills as $biome) {
			$biome->write($out);
		}
	}
}
