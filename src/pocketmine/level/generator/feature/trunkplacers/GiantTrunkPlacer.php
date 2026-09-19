<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\trunkplacers;

use pocketmine\level\ChunkManager;
use pocketmine\level\generator\feature\configurations\TreeConfiguration;
use pocketmine\level\generator\feature\setter\Setter;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class GiantTrunkPlacer extends TrunkPlacer{

	public function type() : TrunkPlacerType {
		return TrunkPlacerType::GIANT_TRUNK_PLACER;
	}

	public function placeTrunk(ChunkManager $level, Setter $trunkSetter, Random $random, int $treeHeight, Vector3 $origin, TreeConfiguration $config) : array {
		$below = $origin->down();
		$this->setDirtAt($level, $trunkSetter, $random, $below, $config);
		$this->setDirtAt($level, $trunkSetter, $random, $below->east(), $config);
		$this->setDirtAt($level, $trunkSetter, $random, $below->south(), $config);
		$this->setDirtAt($level, $trunkSetter, $random, $below->south()->east(), $config);

		for ($hh = 0; $hh < $treeHeight; $hh++) {
			$this->placeLogIfFreeWithOffset($level, $trunkSetter, $random, $config, $origin, 0, $hh, 0);
			if ($hh < $treeHeight - 1) {
				$this->placeLogIfFreeWithOffset($level, $trunkSetter, $random, $config, $origin, 1, $hh, 0);
				$this->placeLogIfFreeWithOffset($level, $trunkSetter, $random, $config, $origin, 1, $hh, 1);
				$this->placeLogIfFreeWithOffset($level, $trunkSetter, $random, $config, $origin, 0, $hh, 1);
			}
		}

		return [new FoliageAttachment($origin->up($treeHeight), 0, true)];
	}

	public function placeLogIfFreeWithOffset(ChunkManager $level, Setter $trunkSetter, Random $random, TreeConfiguration $config, Vector3 $treePos, int $x, int $y, int $z,) : void {
		$this->placeLogIfFree($level, $trunkSetter, $random, $treePos->add($x, $y, $z), $config);
	}
}
