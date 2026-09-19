<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\level\biome\BiomeFactory;
use pocketmine\level\format\Chunk;
use pocketmine\level\generator\placement\HeightmantType;
use pocketmine\math\Vector3;

class IceAndSnowFeature extends Feature {

	public function place(FeaturePlaceContext $context) : bool{
		$level = $context->level();
		$origin = $context->origin();

		$biomeFactory = BiomeFactory::getInstance();
		$chunk = $level->getChunk($origin->getX() >> Chunk::COORD_BIT_SIZE, $origin->getZ() >> Chunk::COORD_BIT_SIZE);

		$ice = BlockFactory::get(BlockIds::ICE);
		$snowLayer = BlockFactory::get(BlockIds::SNOW_LAYER);
		for ($dx = 0; $dx < 16; $dx++) {
			for ($dz = 0; $dz < 16; $dz++) {
				$x = $origin->getX() + $dx;
				$z = $origin->getZ() + $dz;
				$y = HeightmantType::MOTION->getHighestWorkableBlock($level, $x, $z);
				$belowPos = (new Vector3($x, $y, $z))->down();

				$biome = $biomeFactory->get($chunk->getBiomeId($dx, $dz));
				if ($biome->doesWaterFreeze($level, $belowPos, false)) {
					$level->setBlockAt($belowPos->getFloorX(), $belowPos->getFloorY(), $belowPos->getFloorZ(), $ice);
				}

				if ($biome->doesSnowGenerate($level, new Vector3($x, $y, $z))) {
					$level->setBlockAt($x, $y, $z, $snowLayer);
				}
			}
		}

		return true;
	}
}
