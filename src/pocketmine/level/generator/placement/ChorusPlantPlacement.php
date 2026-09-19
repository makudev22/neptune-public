<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\level\format\Chunk;
use pocketmine\level\generator\EndIslandNoiseGenerator;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class ChorusPlantPlacement extends PlacementModifier {

	public function __construct(){

	}

	public function getPositions(PlacementContext $context, Random $random, Vector3 $origin) : array{
		$chunkX = $origin->getFloorX() >> Chunk::COORD_BIT_SIZE;
		$chunkZ = $origin->getFloorZ() >> Chunk::COORD_BIT_SIZE;
		if ($chunkX * $chunkX + $chunkZ * $chunkZ <= 4096) {
			return [];
		}

		$generator = $context->getGenerator();
		if ($generator instanceof EndIslandNoiseGenerator) {
			if ($generator->getIslandHeightValue($chunkX, $chunkZ, 1, 1) > 40.0) {
				return [$origin];
			}
		}

		return [];
	}
}
