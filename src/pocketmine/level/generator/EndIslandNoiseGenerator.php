<?php


declare(strict_types=1);

namespace pocketmine\level\generator;

use pocketmine\level\generator\noise\synth\SimplexNoise;

interface EndIslandNoiseGenerator{

	public function getIslandNoise() : ?SimplexNoise;

	public function getIslandHeightValue(int $chunkX, int $chunkZ, int $subSectionX, int $subSectionZ) : float;
}
