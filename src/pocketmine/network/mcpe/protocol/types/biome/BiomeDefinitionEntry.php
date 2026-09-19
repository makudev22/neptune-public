<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\biome;

use pocketmine\network\mcpe\protocol\types\biome\chunkgen\BiomeDefinitionChunkGenData;
use pocketmine\utils\Color;

final class BiomeDefinitionEntry
{
	/**
	 * @param string[]|null $tags
	 * @phpstan-param list<string>|null $tags
	 */
	public function __construct(
		private string $biomeName,
		private ?int $id,
		private float $temperature,
		private float $downfall,
		private float $foliageSnow,
		private float $redSporeDensity,
		private float $blueSporeDensity,
		private float $ashDensity,
		private float $whiteAshDensity,
		private float $depth,
		private float $scale,
		private Color $mapWaterColor,
		private bool $rain,
		private ?array $tags,
		private ?BiomeDefinitionChunkGenData $chunkGenData = null
	) {
	}

	public function getBiomeName() : string
	{
		return $this->biomeName;
	}

	public function getId() : ?int
	{
		return $this->id;
	}

	public function getTemperature() : float
	{
		return $this->temperature;
	}

	public function getDownfall() : float
	{
		return $this->downfall;
	}

	public function getFoliageSnow() : float
	{
		return $this->foliageSnow;
	}

	public function getRedSporeDensity() : float
	{
		return $this->redSporeDensity;
	}

	public function getBlueSporeDensity() : float
	{
		return $this->blueSporeDensity;
	}

	public function getAshDensity() : float
	{
		return $this->ashDensity;
	}

	public function getWhiteAshDensity() : float
	{
		return $this->whiteAshDensity;
	}

	public function getDepth() : float
	{
		return $this->depth;
	}

	public function getScale() : float
	{
		return $this->scale;
	}

	public function getMapWaterColor() : Color
	{
		return $this->mapWaterColor;
	}

	public function hasRain() : bool
	{
		return $this->rain;
	}

	/**
	 * @return string[]|null
	 * @phpstan-return list<string>|null
	 */
	public function getTags() : ?array
	{
		return $this->tags;
	}

	public function getChunkGenData() : ?BiomeDefinitionChunkGenData
	{
		return $this->chunkGenData;
	}
}
