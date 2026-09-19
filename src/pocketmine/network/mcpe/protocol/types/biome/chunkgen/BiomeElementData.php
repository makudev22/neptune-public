<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\biome\chunkgen;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class BiomeElementData
{
	public function __construct(
		private float $noiseFrequencyScale,
		private float $noiseLowerBound,
		private float $noiseUpperBound,
		private int $heightMinType,
		private int $heightMin,
		private int $heightMaxType,
		private int $heightMax,
		private BiomeSurfaceMaterialData $surfaceMaterial,
	) {
	}

	public function getNoiseFrequencyScale() : float
	{
		return $this->noiseFrequencyScale;
	}

	public function getNoiseLowerBound() : float
	{
		return $this->noiseLowerBound;
	}

	public function getNoiseUpperBound() : float
	{
		return $this->noiseUpperBound;
	}

	public function getHeightMinType() : int
	{
		return $this->heightMinType;
	}

	public function getHeightMin() : int
	{
		return $this->heightMin;
	}

	public function getHeightMaxType() : int
	{
		return $this->heightMaxType;
	}

	public function getHeightMax() : int
	{
		return $this->heightMax;
	}

	public function getSurfaceMaterial() : BiomeSurfaceMaterialData
	{
		return $this->surfaceMaterial;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$noiseFrequencyScale = $in->getLFloat();
		$noiseLowerBound = $in->getLFloat();
		$noiseUpperBound = $in->getLFloat();
		$heightMinType = $in->getVarInt();
		$heightMin = $in->getLShort();
		$heightMaxType = $in->getVarInt();
		$heightMax = $in->getLShort();
		$surfaceMaterial = BiomeSurfaceMaterialData::read($in);

		return new self(
			$noiseFrequencyScale,
			$noiseLowerBound,
			$noiseUpperBound,
			$heightMinType,
			$heightMin,
			$heightMaxType,
			$heightMax,
			$surfaceMaterial
		);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putLFloat($this->noiseFrequencyScale);
		$out->putLFloat($this->noiseLowerBound);
		$out->putLFloat($this->noiseUpperBound);
		$out->putVarInt($this->heightMinType);
		$out->putLShort($this->heightMin);
		$out->putVarInt($this->heightMaxType);
		$out->putLShort($this->heightMax);
		$this->surfaceMaterial->write($out);
	}
}
