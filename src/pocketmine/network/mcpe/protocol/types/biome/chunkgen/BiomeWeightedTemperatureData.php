<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\biome\chunkgen;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class BiomeWeightedTemperatureData
{
	public function __construct(
		private int $temperature,
		private int $weight,
	) {
	}

	public function getTemperature() : int
	{
		return $this->temperature;
	}

	public function getWeight() : int
	{
		return $this->weight;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$temperature = $in->getVarInt();
		$weight = $in->getLInt();

		return new self(
			$temperature,
			$weight
		);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putVarInt($this->temperature);
		$out->putLInt($this->weight);
	}
}
