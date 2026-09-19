<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\biome\chunkgen;

use pocketmine\network\mcpe\NetworkBinaryStream;
use function count;

final class BiomeConsolidatedFeaturesData
{
	/**
	 * @param BiomeConsolidatedFeatureData[] $features
	 */
	public function __construct(
		private array $features,
	) {
	}

	/**
	 * @return BiomeConsolidatedFeatureData[]
	 */
	public function getFeatures() : array
	{
		return $this->features;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$features = [];

		for ($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i) {
			$features[] = BiomeConsolidatedFeatureData::read($in);
		}

		return new self($features);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putUnsignedVarInt(count($this->features));
		foreach ($this->features as $feature) {
			$feature->write($out);
		}
	}
}
