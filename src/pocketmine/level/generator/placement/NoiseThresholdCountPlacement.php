<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\level\biome\BiomeNoise;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class NoiseThresholdCountPlacement extends RepeatingPlacement {

	public static function of(float $noiseLevel, int $belowNoise, int $aboveNoise) : NoiseThresholdCountPlacement {
		return new self($noiseLevel, $belowNoise, $aboveNoise);
	}

	public function __construct(
		private float $noiseLevel,
		private int $belowNoise,
		private int $aboveNoise
	){}

	protected function count(Random $random, Vector3 $origin) : int{
		$flowerNoise = BiomeNoise::getInstance()->getInfoNoise()->getValue2D($origin->getX() / 200.0, $origin->getZ() / 200.0);
		return $flowerNoise < $this->noiseLevel ? $this->belowNoise : $this->aboveNoise;
	}
}
