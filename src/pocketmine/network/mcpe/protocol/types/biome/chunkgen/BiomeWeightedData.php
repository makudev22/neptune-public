<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\biome\chunkgen;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class BiomeWeightedData
{
	public function __construct(
		private int $biome,
		private int $weight,
	) {
	}

	public function getBiome() : int
	{
		return $this->biome;
	}

	public function getWeight() : int
	{
		return $this->weight;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$biome = $in->getLShort();
		$weight = $in->getLInt();

		return new self(
			$biome,
			$weight
		);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putLShort($this->biome);
		$out->putLInt($this->weight);
	}
}
