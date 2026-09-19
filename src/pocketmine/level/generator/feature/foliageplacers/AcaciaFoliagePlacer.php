<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\foliageplacers;

use pocketmine\level\ChunkManager;
use pocketmine\level\generator\feature\configurations\TreeConfiguration;
use pocketmine\level\generator\feature\setter\CollectorSetter;
use pocketmine\level\generator\feature\trunkplacers\FoliageAttachment;
use pocketmine\utils\Random;

class AcaciaFoliagePlacer extends FoliagePlacer{

	public function type() : FoliagePlacerType {
		return FoliagePlacerType::ACACIA_FOLIAGE_PLACER;
	}

	public function createFoliage(ChunkManager $level, CollectorSetter $foliageSetter, Random $random, TreeConfiguration $config, int $treeHeight, FoliageAttachment $foliageAttachment, int $foliageHeight, int $leafRadius, int $offset) : void{
		$doubleTrunk = $foliageAttachment->doubleTrunk();
		$foliagePos = $foliageAttachment->pos()->up($offset);
		$this->placeLeavesRow($level, $foliageSetter, $random, $config, $foliagePos, $leafRadius + $foliageAttachment->radiusOffset(), -1 - $foliageHeight, $doubleTrunk);
		$this->placeLeavesRow($level, $foliageSetter, $random, $config, $foliagePos, $leafRadius - 1, -$foliageHeight, $doubleTrunk);
		$this->placeLeavesRow($level, $foliageSetter, $random, $config, $foliagePos, $leafRadius + $foliageAttachment->radiusOffset() - 1, 0, $doubleTrunk);
	}

	public function foliageHeight(Random $random, int $treeHeight, TreeConfiguration $config) : int{
		return 0;
	}

	public function shouldSkipLocation(Random $random, int $dx, int $y, int $dz, int $currentRadius, bool $doubleTrunk) : bool{
		return $y == 0 ? ($dx > 1 || $dz > 1) && $dx != 0 && $dz != 0 : $dx == $currentRadius && $dz == $currentRadius && $currentRadius > 0;
	}
}
