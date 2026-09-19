<?php


declare(strict_types=1);

namespace pocketmine\level\biome;

class BiomeClimate {
	public function __construct(
		public RainType $precipitation,
		public float $temperature,
		public float $downfall,
	){}
}
