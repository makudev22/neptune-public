<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\trunkplacers;

use pocketmine\level\ChunkManager;
use pocketmine\level\generator\feature\configurations\TreeConfiguration;
use pocketmine\level\generator\feature\setter\Setter;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class StraightTrunkPlacer extends TrunkPlacer{

	public function type() : TrunkPlacerType {
		return TrunkPlacerType::STRAIGHT_TRUNK_PLACER;
	}

	public function placeTrunk(ChunkManager $level, Setter $trunkSetter, Random $random, int $treeHeight, Vector3 $origin, TreeConfiguration $config) : array {
		$this->setDirtAt($level, $trunkSetter, $random, $origin->down(), $config);

		for ($y = 0; $y < $treeHeight; $y++) {
			$this->placeLog($level, $trunkSetter, $random, $origin->up($y), $config);
		}

		return [new FoliageAttachment($origin->up($treeHeight), 0, false)];
	}
}
