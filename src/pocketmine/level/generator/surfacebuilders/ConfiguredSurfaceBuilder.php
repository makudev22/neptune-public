<?php


declare(strict_types=1);

namespace pocketmine\level\generator\surfacebuilders;

use pocketmine\block\Block;
use pocketmine\level\biome\Biome;
use pocketmine\level\format\Chunk;
use pocketmine\utils\Random;

class ConfiguredSurfaceBuilder {

	public function __construct(
		private SurfaceBuilder $builder,
		private SurfaceBuilderConfig $config
	){}

	public function buildSurface(Random $random, Chunk $chunk, Biome $biome, int $x, int $z, int $startHeight, float $noise, Block $defaultBlock, Block $defaultFluid, int $seaLevel, int $seed) : void {
		$this->builder->buildSurface($random, $chunk, $biome, $x, $z, $startHeight, $noise, $defaultBlock, $defaultFluid, $seaLevel, $seed, $this->config);
	}

	public function setSeed(int $seed) : void {
		$this->builder->setSeed($seed);
	}

	public function getConfig() : SurfaceBuilderConfig {
		return $this->config;
	}
}
