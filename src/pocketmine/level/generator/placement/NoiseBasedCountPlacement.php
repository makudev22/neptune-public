<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\level\biome\BiomeNoise;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use function ceil;

class NoiseBasedCountPlacement extends RepeatingPlacement {

	public static function of(float $noiseToCountRatio, int $noiseFactor, int $noiseOffset) : NoiseBasedCountPlacement {
		return new self($noiseToCountRatio, $noiseFactor, $noiseOffset);
	}

	public function __construct(
		private float $noiseToCountRatio,
		private int $noiseFactor,
		private int $noiseOffset
	){}

	protected function count(Random $random, Vector3 $origin) : int{
		$flowerNoise = BiomeNoise::getInstance()->getInfoNoise()->getValue2D($origin->getX() / $this->noiseFactor, $origin->getZ() / $this->noiseFactor);
		return (int) ceil(($flowerNoise + $this->noiseOffset) * $this->noiseToCountRatio);
	}
}
