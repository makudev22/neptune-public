<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class SurfaceWaterDepthFilter extends PlacementFilter {

	public function __construct(
		private int $maxWaterDepth
	){}

	public function shouldPlace(PlacementContext $context, Random $random, Vector3 $origin) : bool{
		$yOceanFloor = HeightmantType::OCEAN_SOLID->getHighestWorkableBlock($context->getLevel(), $origin->getX(), $origin->getZ());
		$ySurfaceFloor = HeightmantType::SOLID->getHighestWorkableBlock($context->getLevel(), $origin->getX(), $origin->getZ());
		return $ySurfaceFloor - $yOceanFloor <= $this->maxWaterDepth;
	}
}
