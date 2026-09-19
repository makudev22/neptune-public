<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\foliageplacers;

use pocketmine\level\ChunkManager;
use pocketmine\level\generator\feature\configurations\TreeConfiguration;
use pocketmine\level\generator\feature\setter\CollectorSetter;
use pocketmine\level\generator\feature\trunkplacers\FoliageAttachment;
use pocketmine\utils\Random;
use function pow;

class FancyFoliagePlacer extends BlobFoliagePlacer{

	public function type() : FoliagePlacerType {
		return FoliagePlacerType::FANCY_FOLIAGE_PLACER;
	}

	public function createFoliage(ChunkManager $level, CollectorSetter $foliageSetter, Random $random, TreeConfiguration $config, int $treeHeight, FoliageAttachment $foliageAttachment, int $foliageHeight, int $leafRadius, int $offset) : void{
		for ($yo = $offset; $yo >= $offset - $foliageHeight; $yo--) {
			$currentRadius = $leafRadius + ($yo != $offset && $yo != $offset - $foliageHeight ? 1 : 0);
			$this->placeLeavesRow($level, $foliageSetter, $random, $config, $foliageAttachment->pos(), $currentRadius, $yo, $foliageAttachment->doubleTrunk());
		}
	}

	public function shouldSkipLocation(Random $random, int $dx, int $y, int $dz, int $currentRadius, bool $doubleTrunk) : bool{
		return pow($dx + 0.5, 2) + pow($dz + 0.5, 2) > $currentRadius * $currentRadius;
	}
}
